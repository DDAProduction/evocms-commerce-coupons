<?php

namespace EvolutionCMS\EvocmsCommerceCoupons\Rules;

class RulesLoader
{
    /**
     * @return Rule[]
     */
    public function loadRules()
    {

        $dir = __DIR__;
        $namespace = 'EvolutionCMS\EvocmsCommerceCoupons\Rules';

        $rules = [];
        $folders = glob(__DIR__ . '/*', GLOB_ONLYDIR);

        foreach ($folders as $folder) {
            if (preg_match('~/([^/]*)Rule$~ui', $folder, $matches)) {

                $ruleName = $matches[1];
                $ruleAlias = lcfirst($ruleName);


                $rules[$ruleAlias] = new Rule(
                    $namespace . '\\' . $ruleName . 'Rule\\' . $ruleName . 'RuleController',
                    $namespace . '\\' . $ruleName . 'Rule\\' . $ruleName . 'RuleModuleController',
                    'EvocmsCommerceCoupons::rules.'.$this->parseView($ruleAlias)
                );
            }
        }

        return $rules;
    }

    /**
     * @param Rule[] $rules
     */
    public function initRules($rules){
        foreach ($rules as $rule) {
            $rule->getModuleController()->init();
        }
    }

    private function parseView(string $ruleAlias)
    {
        $parts = preg_split('/(?=[A-Z])/',$ruleAlias);
        $parts = array_map('lcfirst',$parts);

        return implode('_',$parts);
    }
}