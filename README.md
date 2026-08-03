# منصة تعليم لغة مع محادثات وحساب النقاط
==========================

### Overview & Project Purpose

منصة تعليم لغة مع محادثات وحساب النقاط هي منصة تعليمية متكاملة تقدم فرصًا للطلاب لتعلم اللغات بشكل فعال من خلال محادثات مع متحدثين اللغة الأصلية. تتيح المنصة حساب النقاط للطلاب لتحسين مهاراتهم في اللغة وتحفيزهم على الاستمرار في التعلم.

### Project Structure Mapping


.
├── app
│   ├── __init__.py
│   ├── models
│   │   ├── __init__.py
│   │   ├── user.py
│   │   ├── language.py
│   │   └── conversation.py
│   ├── routes
│   │   ├── __init__.py
│   │   ├── user_routes.py
│   │   ├── language_routes.py
│   │   └── conversation_routes.py
│   ├── services
│   │   ├── __init__.py
│   │   ├── user_service.py
│   │   ├── language_service.py
│   │   └── conversation_service.py
│   ├── utils
│   │   ├── __init__.py
│   │   └── database.py
│   └── main.py
├── config
│   ├── __init__.py
│   └── settings.py
├── docker
│   ├── Dockerfile
│   └── docker-compose.yml
├── requirements.txt
└── README.md


### Step-by-Step Instructions for Running the Environment

1. **Install Docker and Docker Compose**: تأكد من أنك قمت بتثبيت Docker و Docker Compose على جهازك.
2. **Build the Docker Image**: انتقل إلى المجلد `docker` واكتب الأمر `docker-compose build` لإنشاء صورة Docker.
3. **Run the Docker Container**: اكتب الأمر `docker-compose up` لتشغيل الحاوية Docker.
4. **Access the Application**: افتح متصفحك واكتب `http://localhost:5000` لوصول إلى التطبيق.

### Modules, Tables, and Roles

#### Modules

*   `user`: يحتوي على تعريفات للمستخدمين.
*   `language`: يحتوي على تعريفات للغات.
*   `conversation`: يحتوي على تعريفات للمحادثات.

#### Tables

*   `users`: يحتوي على بيانات المستخدمين.
*   `languages`: يحتوي على بيانات اللغات.
*   `conversations`: يحتوي على بيانات المحادثات.
*   `user_languages`: يحتوي على بيانات العلاقات بين المستخدمين واللغات.
*   `user_conversations`: يحتوي على بيانات العلاقات بين المستخدمين والمحادثات.

#### Roles

*   `admin`: يمتلك صلاحيات إدارية كاملة.
*   `teacher`: يمتلك صلاحيات التدريس.
*   `student`: يمتلك صلاحيات الطلاب.

### Contact Developer Details

*   **Developer Name**: [اسم المطور]
*   **Email**: [بريد الإلكتروني]
*   **Phone**: [رقم الهاتف]
*   **LinkedIn**: [لينكد إن]
*   **GitHub**: [غيت هاب]

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
