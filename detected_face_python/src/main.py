import face_recognition
import cv2
import numpy as np

from PIL import Image

from os import listdir
from os.path import isfile, join
from pathlib import Path

path = './img/categories/1/images/';
out_path = './images/1/';
size = (320, 320)

Path(out_path).mkdir(parents=True, exist_ok=True)

def save_face(face_locations, image_file, path, out_path, resize = False):

    print(image_file)
    im = Image.open(join(path, image_file))
    for top, right, bottom, left in face_locations:
        im_crop = im.crop((left - 30, top  - 50, right  + 30, bottom + 30))

        if(resize):
            im_crop.thumbnail(resize)
        
        im_crop.save(join(out_path, image_file), quality=95)
        break

def get_face_locations(path_image):
    image = face_recognition.load_image_file(path_image)
    face_locations = face_recognition.face_locations(image, number_of_times_to_upsample=0, model="cnn")
    return face_locations

def get_files_list():
    
    files_list = []
    for f in listdir(path):
        if isfile(join(path, f)):
            files_list.append(f)

    return files_list

def detection_and_save_face(files_list, path, out_path, resize):
    for img_item in files_list:
        
        face_locations = get_face_locations(join(path, img_item))
        save_face(face_locations, img_item, path, out_path, resize)


# files_list = get_files_list()
# detection_and_save_face(files_list, path, out_path, size)





# ####################### import matplotlib.pyplot as plt ################
# known_image = face_recognition.load_image_file("./img/categories/1/images/0fPiwOF6cWGUEW5T5Eyha6GwbiELwGNumX26rsir.jpg")
# unknown_image = face_recognition.load_image_file("./img/categories/1/images/0HZe9QnjB7JRUOmJ51tztu3MouVloWXy38JN27yR.jpg")



# baixiaona_encoding = face_recognition.face_encodings(known_image)[0]
# unknown_encoding = face_recognition.face_encodings(unknown_image)[0]
# results = face_recognition.compare_faces([baixiaona_encoding], unknown_encoding)
# if results[0] == True:
#     print ("Yes!")
# else:
#     print ("No!")
###########################################################################


print('Hello Docker')
