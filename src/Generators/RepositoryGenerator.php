<?php

namespace Lemec93\Support\Generators;

use Illuminate\Support\Arr;
use Lemec93\Support\Exceptions\ClassNotExistsException;
use Lemec93\Support\Events\SuccessCreateMessage;

class RepositoryGenerator extends EntityGenerator
{
    public function generate()
    {
        if (!$this->classExists('models', $this->model)) {
            $this->throwFailureException(
                ClassNotExistsException::class,
                "Cannot create {$this->model} Model cause {$this->model} Model does not exists.",
                "Create a {$this->model} Model by himself or run command 'php artisan make:entity {$this->model} --only-model'."
            );
        }

        $repositoryContent = $this->getStub('repository', [
            'entity' => $this->model,
            'fields' => $this->getFields()
        ]);

        $this->saveClass('repositories', "{$this->model}Repository", $repositoryContent);

        event(new SuccessCreateMessage("Created a new Repository: {$this->model}Repository"));
    }

    protected function getFields()
    {
        $simpleSearch = Arr::only($this->fields, ['integer', 'integer-required', 'boolean', 'boolean-required']);

        return [
            'simple_search' => Arr::collapse($simpleSearch),
            'search_by_query' => array_merge($this->fields['string'], $this->fields['string-required']),
            'json' => $this->fields['json']
        ];
    }
}
