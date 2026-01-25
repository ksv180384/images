import os
import keras
import tensorflow as tf
import numpy as np
import cv2
from os.path import join, isfile
from PIL import Image
from pathlib import Path
import base64
from io import BytesIO
import json

from detected_face.services.imege_files import ImageFiles

class SearchPhotos:
    
    def __init__(self, category_id) -> None:
        self.category_id = category_id

        self.path = './img/categories/' + str(category_id) + '/images/' # Путь до папки с полным фото
        self.path_faces = './img/categories/' + str(category_id) + '/faces' # Путь до папки с вырезаным лицом
        self.path_features = './img/categories/' + str(category_id) + '/features/' # Путь до папки с фрагментами для поиско похожих изображений

        # self.path_faces = './images/' + str(category_id) + '/faces' # Путь до папки с вырезаным лицом
        # self.path_features = './images/' + str(category_id) + '/features/' # Путь до папки с фрагментами для поиско похожих изображений

    # Поиск похожих изображений
    def search(self, img_base64):
        image_files = ImageFiles(self.category_id)
        files_list = image_files.getFilesList()

        img_bytes = base64.b64decode(img_base64.split(',')[1])
        img_search = BytesIO(img_bytes)

        test = self.search_image_by_list_images(files_list, img_search)
        return test
    
    # Сохраняем фрагменты изображения для поиска по изображениям
    def save_feature(self, file_name):
        if not os.path.isdir(self.path_features):
            Path(self.path_features).mkdir(parents=True, exist_ok=True)
        
        model = tf.keras.applications.vgg16.VGG16(weights='imagenet', include_top=False)
        features = self.get_features(model, join(self.path, file_name))
        path_features_file = self.path_features + os.path.splitext(file_name)[0] + '.npy'
        np.save(path_features_file, features)

        # image_array = []
        # for image in filelist[:200]:
        #     img = io.imread(join(self.path, image))
        #     img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        #     img = cv2.resize(img, (224,224))
        #     image_array.append(img)
        # image_array = np.array(image_array)
        # image_array = image_array.reshape(image_array.shape[0], 224, 224, 3)
        # image_array = image_array.astype('float32')
        # image_array /= 255
        # return np.array(image_array)
    

    # Поиск похожих изображений
    def search_image_by_list_images(self, files_list, img_search):

        if not os.path.isdir(self.path_features):
            Path(self.path_features).mkdir(parents=True, exist_ok=True)

        ressult = []

        # Load list of images to search for similarity
        image_filenames = files_list

        # Load pre-trained VGG16 model
        model = tf.keras.applications.vgg16.VGG16(weights='imagenet', include_top=False)

        # Load reference image
        # ref_img_path = './img/categories/1/images/0fPiwOF6cWGUEW5T5Eyha6GwbiELwGNumX26rsir.jpg'
        # ref_path_features_file = self.path_features + os.path.splitext('0fPiwOF6cWGUEW5T5Eyha6GwbiELwGNumX26rsir')[0] + '.npy'

        # if not isfile(ref_path_features_file):
        #     ref_features = self.get_features(model, ref_img_path)
        #     np.save(ref_path_features_file, ref_features)
        # else:
        #     ref_features = np.load(ref_path_features_file)

        ref_features = self.get_features(model, img_search)

        # Loop over all images to search for similarity
        for filename in image_filenames:
            path_features_file = self.path_features + os.path.splitext(filename)[0] + '.npy'
            if not isfile(path_features_file):
                # Preprocess current image
                features = self.get_features(model, join(self.path, filename))
                np.save(path_features_file, features)
            else:
                features = np.load(path_features_file)
            
            # Calculate cosine similarity between current image features and reference image features
            similarity = np.dot(features.flatten(), ref_features.flatten()) / (np.linalg.norm(features) * np.linalg.norm(ref_features))
            # ressult.append(f"{filename} similarity score: {similarity}")
            p = self.path.replace('./img/', '') + filename
            s = f"{similarity}"
            ressult.append({"similarity": s, "path": p})

        sorted_ressult = sorted(ressult, key=lambda x: x['similarity'], reverse=True)
        ressult = sorted_ressult[0:20]
        return ressult

    
    def delete_photo(self, fila_name):

        path_file_face = join(self.path_faces, fila_name)
        path_file_features = join(self.path_features, os.path.splitext(fila_name)[0] + '.npy')

        if os.path.isfile(path_file_face):
            os.remove(path_file_face)

        if os.path.isfile(path_file_features):
            os.remove(path_file_features)

        return path_file_features


    # Получаем фрагменты выбранные из изображения для писка похожих изображений
    # model - Предобученная модель для получения фрагметов
    # file - Полный путь к файлу
    def get_features(iself, model, img_path):
        img = tf.keras.preprocessing.image.load_img(img_path, target_size=(128, 128))
        img = tf.keras.preprocessing.image.img_to_array(img)
        img = np.expand_dims(img, axis=0)
        img = tf.keras.applications.vgg16.preprocess_input(img)
        return model.predict(img)




