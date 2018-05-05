# Noticeup Image Upload
## Easily updates images of goods opencart

### EN
As you know in opencart it is very difficult to add products especially image. 

This module speeds up the work related to adding images. 
You can add 100 images plus 200 additional images to your items per hour. I think this module is a good find for the content maker whose adding images takes a lot of time.

Before use, be sure to read the documentation

-----

### RU
Как вы знаете в opencart очень трудно добавлять товары особенно изображении. 

Данный модуль ускоряет работу связанные с добавлением изображений. 
Можно добавить 100 изображений плюс еще 200 дополнительных изображений к товаром за час. Думаю данный модуль хорошая находка для контентшика у которого добавления изображений берет уйму времени.

Перед применением обязательно прочтите документацию

#### Документация
Все изображения должны находиться в папке ```image/catalog/products```
Файлы поддерживают любой тип **png, jpg, jpeg** и.т.д 

**Название файла должен быть**
* 100.jpg 		  //Главное изображения
* 100 (2).jpg 	//Второе изображения
* 100 (n).jpg 	//N изображения

По умолчанию регулярные выражения такое `/([0-9]+)\s.([0-9]+)./`

Вы можете прописать свою регулярное выражения на странице
```admin/controller/extension/module/noticeup_upload_image.php строка 85```

Все файлы заружаем в папку `image/catalog/products`

Заходим в админку нажимаем желтую кнопку и ждем список отчетов
```diff
- Предупреждение! При клике на кнопку, таблица ".DB_PREFIX."product_image очищается полностью ("TRUNCATE TABLE ".DB_PREFIX."product_image").
+ Используйте после бэкапа
```
