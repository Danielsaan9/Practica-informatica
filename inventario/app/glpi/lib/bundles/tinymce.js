// 'tinymce' and 'tinyMCE' objects have to be declared in global scope
window.tinymce = window.tinyMCE = require('tinymce/tinymce');

// Default icons
require('tinymce/icons/default/icons');

// Default model
require('tinymce/models/dom');

// Base theme / skin
require('tinymce/themes/silver/theme');

// Used plugins
require('tinymce/plugins/autolink');
require('tinymce/plugins/autoresize');
require('tinymce/plugins/code');
require('tinymce/plugins/directionality');
require('tinymce/plugins/emoticons');
require('tinymce/plugins/emoticons/js/emojis');
require('tinymce/plugins/fullscreen');
require('tinymce/plugins/image');
require('tinymce/plugins/link');
require('tinymce/plugins/lists');
require('tinymce/plugins/quickbars');
require('tinymce/plugins/searchreplace');
require('tinymce/plugins/table');
