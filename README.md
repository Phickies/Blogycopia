# Switching to Modular MVC model instead of traditional MVC

Each page or feature of the website is treated as an individual module. The website can then be plugged and play with different module.

The core is the middle man containing classes of each modules for managing modules. Avoid duplication and create standazation between modules.

## TODO

Making the router also inside each module so that it can be plug and play.
`   - Making that you only need to specify the sub router to the core router in the index.html
Making the interaction between login, register, and home page together
