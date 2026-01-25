from django.urls import path
from detected_face.views import index, get_face

from .views import *

urlpatterns = [
    path('detected/', index, name='detected-face'),
    path('add-image/', add_image, name='add-image'),
    path('face/', get_face, name='get-face'),
    path('face-crop', face_crop, name='face-crop'),
    path('face-detected-all-by-category', face_detected_all_by_category, name='face-detected-all-by-category'),
    # path('training-search-by-photo', training_search_by_photo, name='training-search-by-photo'),
    path('search-by-photo', search_by_photo, name='search-by-photo'),
    path('delete-photo/', delete_photo, name='delete-photo'),
    path('get-image-info', getImageInfo, name='get-image-info'),
]

# pip install django-cors-headers
# pip install tensorflow