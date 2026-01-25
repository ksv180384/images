FROM python:3.11.3

COPY ./detected_face_python/requirements.txt /temp/requirements.txt
COPY ./detected_face_python/src /app

RUN apt-get update && apt-get install -y \
    cmake \
    libopenblas-dev \
    liblapack-dev \
    libjpeg-dev \
    libpng-dev \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN pip install --upgrade pip

RUN pip install numpy opencv-python-headless face_recognition

RUN pip install -r /temp/requirements.txt

RUN  pip install django-cors-headers
RUN  pip install tensorflow

WORKDIR /app

RUN adduser --disabled-password ksv180384
USER ksv180384

EXPOSE 8001