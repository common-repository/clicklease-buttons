<?php

if (!class_exists('CLSWP_ViewPage')) :

    abstract class CLSWP_ViewPage
    {
        public string $token;
        public bool $withInfoPage;
        private $clkMobBtnBuilder;


        protected function __construct()
        {
            $this->execHooks();
            $this->token = '';
            $this->withInfoPage = '';
            $this->clkMobBtnBuilder =  (new CLSWP_ButtonIframeBuilder());
        }

        /**
         * Adds the iframe which opens the clicklease application to apply for a leasing
         * to every pages where it is executed
         */
        public function getRenderScript(string $customScript = '', array $functParams = [])
        {
            //error_log(print_r('getRenderScript: ' . json_encode($functParams)), true);
?>
            <script id="render-clicklease-ebuttons-script">
                const clsAppendBtnsCLick = setInterval(() => {
                    try {
                        clsGlobalObj.registerClsBtnFuncionality();
                        clearInterval(clsAppendBtnsCLick);
                    } catch (err) {
                        console.log('%ccls e-buttons-plugin error', 'background: red;');
                        console.log(err);
                    }
                }, 700)
                setTimeout(() => clearInterval(clsAppendBtnsCLick), 25 * 1000);
            </script>
<?php
        }

        public abstract function startCustomScripts();
        public abstract function execHooks();
    }

endif;
