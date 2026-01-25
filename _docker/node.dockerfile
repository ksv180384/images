# base image
FROM node:22.16.0-alpine

# set working directory
WORKDIR /imagines

# add `/app/node_modules/.bin` to $PATH
ENV PATH /imagines/node_modules/.bin:$PATH

# install and cache app dependencies
COPY ./frontend/package.json /imagines/package.json
RUN npm install

COPY ./frontend ./imagines

# start app
CMD ["npm", "run", "dev"]