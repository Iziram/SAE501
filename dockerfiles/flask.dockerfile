FROM python:3.11.1
WORKDIR /code
COPY ./flask /code/app

RUN pip install --no-cache-dir --upgrade -r /code/app/requirements.txt
WORKDIR /code/app

CMD ["waitress-serve", "--listen=0.0.0.0:80", "main:app"]
