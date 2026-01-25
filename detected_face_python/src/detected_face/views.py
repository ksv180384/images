from django.shortcuts import render
from django.http import HttpResponse, HttpResponseNotFound
import json
from os.path import join

from detected_face.services.detected_face import DetectedFace
from detected_face.services.face import Face
from detected_face.services.search_photos import SearchPhotos
from detected_face.services.imege_files import ImageFiles

# Определяем положения лиц на изображении
def index(request):
    category_id = request.GET.get('category_id') 
    image = request.GET.get('file_name')

    detected_face = DetectedFace(category_id, image)
    face_locations = detected_face.detection_face_by_foto()
    return HttpResponse(json.dumps(face_locations), content_type="application/json")

# При добавлении нового изображения вырезаем лицо и сохраняем, 
# создаем файл с фрагментами изображения для возможности поиска похожих изображений 
def add_image(request):
    json_data = json.loads(request.body)
    category_id = str(json_data['category_id'])
    image_file = str(json_data['file_name'])

    # Получаем координаты изображения
    detected_face = DetectedFace(category_id, image_file)
    face_locations = detected_face.detection_face_by_foto()
    # Сохраняем лицо с изображения
    detected_face.save_face(
        face_locations, 
        detected_face.image, 
        detected_face.path, 
        detected_face.out_path_faces, 
        detected_face.size
    )

    # Сохраняем фрагменты изображения
    search_photos = SearchPhotos(category_id)
    search_photos.save_feature(image_file)

    return HttpResponse(json.dumps({'status': 'ok'}), content_type='application/json')


# Возвращаем изображение лица определенного изображения
def get_face(request):
    category_id = request.GET.get('category_id')
    file_name = request.GET.get('file_name') 

    face = Face(category_id, file_name)
    face_base_64 = face.get_image_face()
    return HttpResponse(
        json.dumps(
            { 'image': 'data:image/png;base64,' + face_base_64}
        ), 
        content_type='application/json'
    )

# Вырезаем лицо из изображения по координатам
def face_crop(request):
    # category_id = request.POST.get('category_id')
    # file_name = request.POST.get('file_name')
    # position = request.POST.getlist('position[]')

    json_data = json.loads(request.body)
    category_id = str(json_data['category_id'])
    file_name = str(json_data['file_name'])
    position = tuple(json_data['position'])

    # positions = [tuple(position)]
    positions = [position]
    detected_face = DetectedFace(category_id, file_name, is_update=True)
    path = detected_face.path
    out_path = detected_face.out_path_faces
    size = detected_face.size

    detected_face.save_face(
        positions, 
        file_name, 
        path, 
        out_path,
        size
    )
    return HttpResponse(json.dumps({'message': 'ok' }), content_type='application/json')

# Вырезаем лица из всех изображений категории
def face_detected_all_by_category(request):
    category_id = request.POST.get("category_id")

    detected_face = DetectedFace(category_id)
    res = detected_face.run_detected_and_save_all_faces_by_category()
    return HttpResponse(json.dumps({'message': res}), content_type='application/json')


# Поиск похожих изображений
def search_by_photo(request):
    json_data = json.loads(request.body)
    category_id = str(json_data['category_id'])
    img_base64 = str(json_data['img_base64'])
    
    search_photos = SearchPhotos(category_id)
    res_search = search_photos.search(img_base64)
    return HttpResponse(json.dumps(res_search), content_type='application/json')

    # detected_face = DetectedFace(category_id)
    # res = detected_face.test2(img_base64)
    # return HttpResponse(json.dumps({'fsd':'asdas'}), content_type='application/json')

# Удаляем данные относящиеся к изображению (вырезаное лицо, данные фрагментов изображения)
def delete_photo(request):
    json_data = json.loads(request.body)
    category_id = str(json_data['category_id'])
    
    search_photos = SearchPhotos(category_id)
    res = search_photos.delete_photo(json_data['image'])
    
    return HttpResponse(json.dumps({'message': res}), content_type='application/json')

def getImageInfo(request):
    json_data = json.loads(request.body)
    category_id = str(json_data['category_id'])
    file_name = str(json_data['file_name'])

    image_files = ImageFiles(category_id)
    image_info = image_files.getFileMetadata(file_name)

    # print(image_info)

    return HttpResponse(image_info, content_type='application/json')

def pageNotFound(request, exeption):
    return HttpResponseNotFound('Такой страницы не существует')
