# base image
FROM node:22.16.0-alpine

WORKDIR /imagines

COPY ./frontend2/package*.json ./

# Устанавливаем зависимости
RUN npm install

# Теперь копируем остальные файлы
COPY ./frontend2 ./

ENV PATH /imagines/node_modules/.bin:$PATH

CMD ["npm", "run", "dev"]