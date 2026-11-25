import psycopg2

def connect_main():
    return psycopg2.connect(
        host="localhost",
        port="5432",
        dbname="unidesk",
        user="group12",
        password="thecakegroup"
    )

def connect_middleman():
    return psycopg2.connect(
        host="localhost",
        port="5433",
        dbname="middleman",
        user="middleman_user",
        password="middleman_secret"
    )