import { defineStore } from 'pinia';

export const useImagesStore = defineStore('imagesStore', {
    state: () => ({
        category_id: 0,
        images: []
    }),
    actions: {
        setCategoryId(categoryId){
            this.category_id = categoryId;
        },
        setImages(images){
            this.images = images;
        },
        addImage(image){
            this.images.push(image);
        },
        updateImage(image){
            this.images = this.images.map((item) => {
                if(item.id === image.id){
                    return image;
                }
                return item;
            });
        },
        deleteImage(id){
            this.images = this.images.filter(item => item.id !== id);
        }
    }
});