Description of Fork Awesome import into Moodle

Fork Awesome comes in 3 parts relating to Moodle.

1. The font. Put the woff font in lib/fonts/forkawesome-webfont.woff. Update lib/thirdpartylibs.xml.
2. SCSS. Replace the SCSS in this folder (/theme/boost/scss/forkawesome). Update theme/boost/thirdpartylibs.xml.
3. Edit /theme/boost/scss/forkawesome/fork-awesome.scss and comment out the `@import "path"` line.
   This is because we provide the font path differently e.g. "[[font:core|forkawesome-webfont.eot]]"
