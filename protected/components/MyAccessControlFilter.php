<?php

class MyAccessControlFilter extends CAccessControlFilter {

    protected function preFilter($filterChain) {
        $app = Yii::app();
        $request = $app->getRequest();
        $user = $app->getUser();
        $verb = $request->getRequestType();
        $ip = $request->getUserHostAddress();

        foreach ($this->getRules() as $rule) {
            if (($allow = $rule->isUserAllowed($user, $filterChain->controller, $filterChain->action, $ip, $verb)) > 0) // allowed
                break;
            else if ($allow < 0) { // denied
                // CODE CHANGED HERE
                $request->redirect($app->createUrl($rule->redirect[0]));
                return false;
            }
        }

        return true;
    }

}

?>
