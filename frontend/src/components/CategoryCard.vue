<template>
  <router-link
      :to="{ name: 'admin.category.edit', params: { id: id } }"
      class="card-item border border-violet-300 rounded-xl shadow-md"
  >

    <div class="card-content p-4">
      <h3 class="mb-0">{{ title }}</h3>

      <p class="card-text mt-2 mb-auto">
        {{ description }}
      </p>
    </div>
    <div v-if="url" class="img-block d-block h-100">
      <img class="img-card" :alt="title" :src="url"/>
    </div>

  </router-link>
</template>

<script setup lang="ts">
const { id, title, description, url } = defineProps<{ id: number, title: string, description: string, url: string }>();
</script>

<style scoped>
a{
  color: rgba(var(--bs-dark-rgb),var(--bs-text-opacity));
  text-decoration: none;
}

.card-item{
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: flex-start;
  margin-bottom: 10px;
  overflow: hidden;
}

.card-content{
  flex: 1;
  min-width: calc(100% - 240px);
}

.img-block{
  flex: 1 1 200px;
  text-align: right;
}

.img-card{
  max-height: 300px;
  object-fit: cover;
  min-width: 100%;
}

@media (max-width: 1140px) {
  .card-content{
    min-width: calc(100% - 300px);
  }

  .img-block{
    flex: 1 1 300px;
  }

  .img-card{
    max-height: 330px;
    object-position: top;
  }
}

@media (max-width: 768px) {
  .card-item{
    flex-direction: column-reverse;
    flex-wrap: nowrap;
  }

  .img-card{
    max-height: 400px;
    max-width: 100%;
  }

  .img-block{
    flex: 1 1 auto;
  }
}
</style>