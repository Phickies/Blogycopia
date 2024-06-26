# Switching to Hierachy MVC model instead of traditional MVC

Each page or feature of the website is treated as an individual module. The website can then be plugged and play with different module.

The core is the middle man containing classes of each modules for managing modules. Avoid duplication and create standazation between modules.

## TODO

- Find a way to use HomeController to render the view for that particular module. The homeController will create a new method for that and then call the view method from the Core Controller to also add the header and the footer from the Core View class. After that, the whole page is then render.
