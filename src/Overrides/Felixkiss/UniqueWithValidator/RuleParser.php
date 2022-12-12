<?php 

namespace Cortex\Foundation\Overrides\Felixkiss\UniqueWithValidator;

use Illuminate\Database\Eloquent\Model;
use Cortex\Foundation\Models\AbstractModel;
use Felixkiss\UniqueWithValidator\RuleParser as BaseRuleParser;

class RuleParser extends BaseRuleParser
{
    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        $this->parse();
        $table = $this->table;
        if (str_contains($table, '\\') && class_exists($table) && is_a($table, Model::class, true)) {
            $model = new $table();
            $table = $model->getTable();
            return $model ? ($this->isValidationScoped($model) ? $model : $model->withoutGlobalScopes()) : (new AbstractModel())->setTable($table);
        }else{
            return $table;
        }
    }
    /**
     * Returns whether the model validation be scoped or not. (Default: true).
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    protected function isValidationScoped(Model $model): bool
    {
        return $model->isValidationScoped ?? true;
    }
}
