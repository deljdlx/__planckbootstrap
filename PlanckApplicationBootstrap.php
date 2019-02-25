<?php


class PlanckApplicationBootstrap
{


    protected static $instance;

    /**
     * @var \Phi\Core\VirtualPathManager
     */
    protected $virtualPathManager;

    /**
     * @var \Phi\Core\Autoloader
     */
    protected $autoloader;

    /**
     * @var \Planck\Runtime
     */
    protected $runtime;

    protected $applicationPath;
    protected $workingPath;


    public function getInstance(\Phi\Core\Autoloader $autoloader, $applicationPath)
    {
        if(!static::$instance) {
            static::$instance = new static($autoloader, $applicationPath);
        }
        return static::$instance;
    }


    protected function __construct(\Phi\Core\Autoloader $autoloader, $applicationPath)
    {
        $this->autoloader = $autoloader;

        $this->applicationPath = $this->normalizePath($applicationPath);

        if(strpos(
                $this->normalizePath(__DIR__),
                $this->applicationPath
            ) !== false) {
            $this->workingPath = $this->applicationPath;
        }
        else {
            $this->workingPath = $this->normalizePath(realpath(__DIR__.'/..'));
        }

        $this->virtualPathManager = \Phi\Core\VirtualPathManager::getInstance();
        $this->initializePathManager();


        $this->autoloadAll();

        $this->runtime = Planck\Runtime::getInstance();
        $this->runtime->setVariable('pathManager', $this->virtualPathManager);
        $this->runtime->setVariable('autoloader', $this->autoloader);
        $this->runtime->setVariable('sharedPath', \Planck\Helper\File::normalize($this->workingPath));
    }


    protected function normalizePath($path)
    {
        return str_replace('\\', '/', $path);
    }


    public function getRuntime()
    {
        return $this->runtime;
    }

    public function getAutoloader()
    {
        return $this->autoloader;
    }

    public function buildSymlinks()
    {
        $this->virtualPathManager->buildSymlinks();
    }

    public function registerVirtualPath($real, $virtual, $name = null)
    {
        $this->virtualPathManager->registerPath($real, $virtual, $name);
        return $this;
    }



    protected function initializePathManager()
    {

        /*
        $this->virtualPathManager->registerPath(
            $this->workingPath.'/data',
            $this->applicationPath.'/data',
            'data'
        );
        */

        /*
        $this->virtualPathManager->registerPath(
            $this->workingPath.'/public/data',
            $this->applicationPath.'/www/data',
            'front-data'
        );
        */



        $this->virtualPathManager->registerPath(
            $this->workingPath.'/static-vendor',
            $this->applicationPath.'/static-vendor',
            'vendor'
        );

        $this->virtualPathManager->registerPath(
            $this->workingPath.'/extension',
            $this->applicationPath.'/extension',
            'extension'
        );


        $this->virtualPathManager->registerPath(
            $this->workingPath.'/theme',
            $this->applicationPath.'/www/theme',
            'theme'
        );


        $this->virtualPathManager->registerPath(
            $this->workingPath.'/public/planck-front-vendor',
            $this->applicationPath.'/www/vendor',
            'front-vendor'
        );


        return $this->virtualPathManager;




    }



    protected function autoloadAll()
    {

        $staticVendorPath = $this->virtualPathManager->getPathByName('vendor');

        require($staticVendorPath.'/mustache/src/Mustache/Autoloader.php');
        $mustacheAutoloader = new Mustache_Autoloader($staticVendorPath.'/mustache');
        $mustacheAutoloader->register();




        $this->autoloader->addNamespace('Phi\Traits', $staticVendorPath.'/phi/phi-traits/source/class');



        $this->autoloader->addNamespace('Phi\Storage', $staticVendorPath.'/phi/phi-storage/source/class');


        $this->autoloader->addNamespace('Phi\Event', $staticVendorPath.'/phi/phi-event/source/class');


        $this->autoloader->addNamespace('Phi\HTTP', $staticVendorPath.'/phi/phi-http/source/class');
        $this->autoloader->addNamespace('Phi\Routing', $staticVendorPath.'/phi/phi-routing/source/class');
        $this->autoloader->addNamespace('Phi\Session', $staticVendorPath.'/phi/phi-session/source/class');


        $this->autoloader->addNamespace('Phi\Database', $staticVendorPath.'/phi/phi-database/source/class');
        $this->autoloader->addNamespace('Phi\Model', $staticVendorPath.'/phi/phi-model/source/class');



        $this->autoloader->addNamespace('Phi\Cache', $staticVendorPath.'/phi/phi-cache/source/class');
        $this->autoloader->addNamespace('Phi\Container', $staticVendorPath.'/phi/phi-container/source/class');
        $this->autoloader->addNamespace('Phi\Application', $staticVendorPath.'/phi/phi-application/source/class');
        $this->autoloader->addNamespace('Phi\Template', $staticVendorPath.'/phi/phi-template/source/class');


        $this->autoloader->addNamespace('Phi\HTML', $staticVendorPath.'/phi/phi-html/source/class');
        $this->autoloader->addNamespace('Phi\HTML\Extended', $staticVendorPath.'/phi/phi-html-extended/source/class');
//registering extended HTML elements
        require($staticVendorPath.'/phi/phi-html-extended/source/bootstrap.php');



        $this->autoloader->addNamespace('Planck', $staticVendorPath.'/planck/planck/source/class');
        $this->autoloader->addNamespace('Planck\Application', $staticVendorPath.'/planck/planck-application/source/class');

        $this->autoloader->addNamespace('Planck\ApplicationBuilder', $staticVendorPath.'/planck/planck-application-builder/source/class');


        $this->autoloader->addNamespace('Planck\Routing', $staticVendorPath.'/planck/planck-routing/source/class');
        $this->autoloader->addNamespace('Planck\Pattern', $staticVendorPath.'/planck/planck-pattern/source/class');
        $this->autoloader->addNamespace('Planck\Model', $staticVendorPath.'/planck/planck-model/source/class');
        $this->autoloader->addNamespace('Planck\View', $staticVendorPath.'/planck/planck-view/source/class');
        $this->autoloader->addNamespace('Planck\Navigation', $staticVendorPath.'/planck/planck-navigation/source/class');




        $extensionFilepathRoot = $this->virtualPathManager->getPathByName('extension');

        $themeFilepath = $this->virtualPathManager->getPathByName('theme');
        $this->autoloader->addNamespace('Planck\Theme\PlanckBoard', $themeFilepath.'/planck-theme-planck-board/source/class');
        $this->autoloader->addNamespace('Planck\Theme\Yummy', $themeFilepath.'/planck-theme-yummy/source/class');

        $this->autoloader->addNamespace(\Planck\Extension\Bootstrap::class, $extensionFilepathRoot.'/planck/planck-extension-bootstrap/source/class');
        $this->autoloader->addNamespace(\Planck\Extension\Tool::class, $extensionFilepathRoot.'/planck/planck-extension-tool/source/class');
        $this->autoloader->addNamespace(\Planck\Extension\Navigation::class, $extensionFilepathRoot.'/planck/planck-extension-navigation/source/class');
        $this->autoloader->addNamespace(\Planck\Extension\FrontVendor::class, $extensionFilepathRoot.'/planck/planck-extension-front-vendor/source/class');
        $this->autoloader->addNamespace(\Planck\Extension\ViewComponent::class, $extensionFilepathRoot.'/planck/planck-extension-view-component/source/class');

        $this->autoloader->addNamespace(\Planck\Extension\FormComponent::class, $extensionFilepathRoot.'/planck/planck-extension-form-component/source/class');


        $this->autoloader->addNamespace(\Planck\Extension\Model::class, $extensionFilepathRoot.'/planck/planck-extension-model/source/class');
        $this->autoloader->addNamespace(\Planck\Extension\User::class, $extensionFilepathRoot.'/planck/planck-extension-user/source/class');
        $this->autoloader->addNamespace(\Planck\Extension\EntityEditor::class, $extensionFilepathRoot.'/planck/planck-extension-entity-editor/source/class');

        $this->autoloader->addNamespace(\Planck\Extension\StatusManager::class, $extensionFilepathRoot.'/planck/planck-extension-status-manager/source/class');
        $this->autoloader->addNamespace(\Planck\Extension\RichTag::class, $extensionFilepathRoot.'/planck/planck-extension-rich-tag/source/class');
        $this->autoloader->addNamespace(\Planck\Extension\Content::class, $extensionFilepathRoot.'/planck/planck-extension-content/source/class');

        $this->autoloader->addNamespace(\Planck\Extension\CMS::class, $extensionFilepathRoot.'/planck/planck-extension-cms/source/class');





    }


}



