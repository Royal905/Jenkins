from flask import Flask

app = Flask(__name__)

@app.route('/')
def home():
    return '<h1>Welcome to My Simple Website</h1><p>This is a basic website hosted using Flask.</p>'

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)

