# app.py
from flask import Flask, request, render_template, redirect, url_for
from flask_sqlalchemy import SQLAlchemy
import datetime

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///board.db'
db = SQLAlchemy(app)

class Post(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    content = db.Column(db.Text, nullable=False)
    timestamp = db.Column(db.DateTime, default=datetime.datetime.now)

    def __repr__(self):
        return f'<Post {self.id}>'

@app.route('/')
def index():
    posts = Post.query.order_by(Post.timestamp.desc()).all()
    return render_template('index.html', posts=posts)

@app.route('/post', methods=['POST'])
def post():
    content = request.form.get('content')
    if content:
        post = Post(content=content)
        db.create_all()
        db.session.add(post)
        db.session.commit()
    return redirect(url_for('index'))

if __name__ == '__main__':
    app.run(debug=True)
