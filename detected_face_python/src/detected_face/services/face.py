import base64 

class Face:

    def __init__(self, category_id, image):
        self.path = './img/categories/' + str(category_id) + '/faces/' + image
        # self.path = './images/' + str(category_id) + '/faces/' + image
        # self.image = image

    def get_image_face(self):
        image = open(self.path, 'rb') #open binary file in read mode
        image_read = image.read()
        image_64_encode = base64.b64encode(image_read)
        return image_64_encode.decode()

        
    