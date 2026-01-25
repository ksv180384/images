from os import listdir
from os.path import isfile, join
# from PIL import Image
# from PIL.ExifTags import TAGS
# from exif import Image

class ImageFiles():

    def __init__(self, category_id):
        self.category_id = category_id
        self.path = './img/categories/' + category_id + '/images/'

    # Получаем список файлов категории
    def getFilesList(self):
        
        files_list = []
        for f in listdir(self.path):
            if isfile(join(self.path, f)):
                files_list.append(f)

        return files_list
    
    def getFileMetadata(self, file_name):
        
        path_image = join(self.path, file_name)
        
        if not isfile(path_image):
            return False
        
        # open the image
        # image = Image.open(path_image)
        
        # # # extracting the exif metadata
        # exifdata = image.getexif()
        # result = []
        # # ret = {}
        # for tag_id in exifdata:
        #     # get the tag name, instead of human unreadable tag id
        #     tag = TAGS.get(tag_id, tag_id)
        #     data = exifdata.get(tag_id)
        #     # decode bytes 
        #     if isinstance(data, bytes):
        #         data = data.decode()
        #     result.append((tag, data))
        
        # with open(path_image, 'rb') as image_file:
        #     my_image = Image(image_file)

        # my_image.has_exif
            
        # # return ret
        # return my_image.list_all()
        return ''