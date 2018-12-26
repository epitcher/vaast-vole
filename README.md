# Vole

### PHP 5.6+ required due to use of "splat" operator

Vole is heavily influenced by Yii 2, I appreciate the structual guidelines however no Yii 2 code is utilised. Vole is my personal take on how I want a PHP framework to work. No nonsense, straight VC framework with nothing attached.

When I began writing Vole I intended on a MVC system, I have now decided that I don't want to add the models directly into the structure. I intend on releasing models as an extension to vole ( Configured in config/config.php or other main config ) as they are wholly required for majority uses of Vole.

- [x] Command line actions
- [ ]  Model -> Controller -> View **( Dismissed as goal, Models will be extension )**
- [x] Global objects ( Register in config )
- [ ] Pretty URL routing
- [ ] Html templating ( ?? Moustache brackets  ?? )

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
