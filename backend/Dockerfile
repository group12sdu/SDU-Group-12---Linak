FROM python:3.12-slim

WORKDIR /app

COPY library_requirements.txt .
RUN pip install -r library_requirements.txt

COPY . .

EXPOSE 8001

CMD ["python", "main.py"]