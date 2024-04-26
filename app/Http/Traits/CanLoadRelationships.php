<?php 

namespace App\Http\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

trait CanLoadRelationships
{
    public function loadRelationships(
        Model|Builder|QueryBuilder $for,
        ?array $relations = null
    ) : Model|Builder|QueryBuilder {

        // first in checks in $relations is given in argument, if wasn't given, then it checks for $relations property in where trait
        // being used. if it's not ther, then it's value would be an empty array ( [] )
        $relations = $relations ?? $this->relations ?? [];

        foreach($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    
    protected function shouldIncludeRelation(string $relation) : bool
    {
        $include = request()->query('include');

        if(!$include) {
            return false;
        }

        $relations =  array_map('trim',explode(',', $include));

        return in_array($relation, $relations);
    }

}