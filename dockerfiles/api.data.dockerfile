FROM python:3.11.1
WORKDIR /code
COPY ./api/data /code/app

RUN pip install --no-cache-dir --upgrade -r /code/app/requirements.txt

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8080"]
