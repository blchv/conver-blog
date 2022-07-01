# A simple Magento 2 blog module

## How to install

> composer require blchv/convert-blog

## Functionalities

- [x] **1/** Convert_Blog module skeleton
- [x] **2/** Declarative schema for db table structure
- [x] **3/** CRUD models
- [x] **4/** Data Patch inserting 2-3 post records into database
- [x] **5/** Controllers for posts listing and view of separate post
- [x] **6/** Admin panel page with settings that will allow admin for:
    - [x] **a/** enable/disable module
    - [x] **b/** choose field by which posts will sorted in post listing page (id/title/author/content)
- [x] **7/** Web Api endpoints for:
    - [x] **a/** GET /blog/post/:id (specific Post data)
    - [x] **b/** GET /blog/post (list all public Posts)
    - [x] **c/** POST /blog/post (Post creation)
    - [x] **d/** PUT /blog/post (Post edit)

- [x] **8/** "Please declare event when post is viewed and add log entry with statistic to the file"
- [x] **9/** (Optional) Create new blog post after new product is created, in post add information using below template:
    > > Title: "New product is available in our store - product_title
    >
    > > Content: "New product is available in our store <a href="product_url"> product_title</a> [product_sku].

- [x] **10/** (Optional) Admin backend menu containing 2 adminhtml pages:
    - [x] **11/** Backend blogs list (grid)
    - [x] **12/** Backend blog form to add/update Posts
