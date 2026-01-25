import face_recognition
import cv2
import numpy as np

from PIL import Image

import pickle
import os
from os import listdir
from os.path import isfile, join
from pathlib import Path

import base64
from io import BytesIO

class DetectedFace:

    def __init__(self, category_id, image = '', is_update = False):
        self.path = './img/categories/' + str(category_id) + '/images/'
        self.out_path_faces = './img/categories/' + str(category_id) + '/faces'
        # self.out_path_faces = './images/' + str(category_id) + '/faces'
        self.image = image
        self.size = (320, 320)
        self.is_update = is_update

    # Вырезаем и сохраняем лицо
    def save_face(self, face_locations, image_file, path, out_path_faces, resize = False):

        path_image_file = join(path, image_file)
        out_path_image_file = join(out_path_faces, image_file)

        Path(out_path_faces).mkdir(parents=True, exist_ok=True)

        # Если не надо обновлять уже сеществующий файл, то пропускаем его 
        if not self.is_update and isfile(out_path_image_file):
            return True

        im = Image.open(path_image_file)
        for top, right, bottom, left in face_locations:
            im_crop = im.crop((int(left) - 30, int(top)  - 50, int(right)  + 30, int(bottom) + 30))

            if(resize):
                im_crop.thumbnail(resize)
            
            im_crop.save(join(out_path_faces, image_file), quality=95)
            break

        return True

    # Получаем координаты найденых лц на изображении
    def get_face_locations(self, path_image):
        image = face_recognition.load_image_file(path_image)
        face_locations = face_recognition.face_locations(image, number_of_times_to_upsample=0, model="hog")
        if not face_locations:
            face_locations = face_recognition.face_locations(image, number_of_times_to_upsample=0, model="cnn")
        return face_locations
    
    # Получем список файлов в папке
    def get_files_list(self, path):
        
        files_list = []
        for f in listdir(path):
            if isfile(join(path, f)):
                files_list.append(f)

        return files_list

    # Поиск лиц в каждом файле в указанной папке
    def detection_and_save_face(self, files_list, path, out_path_faces, resize):
        for img_item in files_list:
            if not self.is_update and isfile(join(self.out_path_faces, img_item)):
                continue

            face_locations = self.get_face_locations(join(path, img_item))
            self.save_face(face_locations, img_item, path, out_path_faces, resize)
        return True

    def run_detected_and_save_all_faces_by_category(self):
        files_list = self.get_files_list(self.path)
        res = self.detection_and_save_face(files_list, self.path, self.out_path_faces, self.size)
        return res

    # Получаем массив координат найденых лиц на изображении
    def detection_face_by_foto(self):
        img_path = join(self.path, self.image)
        face_locations = self.get_face_locations(img_path)
        return face_locations

    def test(self):
        known_encodings = []
        faces_list = os.listdir(self.out_path_faces)
        for (i, face) in enumerate(faces_list):
            face_image = face_recognition.load_image_file(join(self.out_path_faces, face))
            face_enc = face_recognition.face_encodings(face_image)
            if face_enc:
                known_encodings.append(face_enc[0])

        training_data = {
            'name': 'Rachael Leigh Cook',
            'encodings': known_encodings 
        }

        with open(f"rachael_leigh_cook.pickle", "wb") as file:
            file.write(pickle.dumps(training_data))
        return 'test'
    
    def test2(self, img_base64):
        img_bytes = base64.b64decode(img_base64.split(',')[1])
        img_search = BytesIO(img_bytes)
        image = Image.open(img_search)
        image_array = np.array(image)
        
        training_data = pickle.loads(open('rachael_leigh_cook.pickle', 'rb').read())
        
        locations = self.get_face_locations(img_search)
        
        # encodings = face_recognition.face_encodings(image_array, locations)

        # for face_encoding, face_location in zip(encodings, locations):
        #     result = face_recognition.compare_faces(training_data['encodings'], face_encoding)
        #     match = None

        #     if True in result:
        #         match = training_data['name']
        #         break  

        match = None
        encodings = face_recognition.face_encodings(image_array)
        # print(locations[0])
        result = face_recognition.compare_faces(training_data['encodings'], encodings[0])
        for (i, r) in enumerate(result):
            if r:
                match = 'Rachael Leigh Cook'
                
        print(match)
