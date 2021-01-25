# Vole

### PHP 5.6+ required

### *Vole was a toy micro-framework I'm never touching again* Still feel free to fork Vole and make of it what you will, it is great to hack around with and make do exactly what you want. Vole is simple enough to be able to adapt with any level of PHP knowledge but intricate enough to get things done.

When I began writing Vole I intended on a MVC system, I have now decided that I don't want to add the models directly into the structure as any ORM will be a better use.
- [x] Command line actions
- [ ]  Model -> Controller -> View **( Dismissed as goal, ORMs all the way )**
- [x] Global objects ( Register in config )
- [x] Pretty URL routing
- [ ] Html templating **.. This is what PHP is for..**

This repo contains a working Vole app, the original application structure wrapped around vole-core.
```
vole-core
 |
 |--> base ( Base objects - These should not be modified, unless you are confident to ofcourse )
 |
 |--> core ( Core objects - Feel free to prod at these, they shouldn't break much )
 |
 |--> src ( Vole and BaseVole objects, some utilised functions can be found in Vole.php )
 |
 |--> Application.php ( This is how the process executes, almost anything can be done here )

vole-app
 |
 |--> config ( Default location of main config )
 |      |
 |      |--> config.php ( Main config )
 |
 |--> console ( Command line accessible controllers )
 |
 |--> site ( Site accessible controllers )
 |
 |--> web ( Web accessible assets )
 |
 |--> vaast ( Where vole-core lives, if not installed with composer as it is here )
 |
 |--> .htaccess ( Project level Apache2 configuration )
 |
 |--> vole ( Allows executing vole on command line "./vole controller/action" )
 ```
