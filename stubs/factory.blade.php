$factory->define(App\Models\{{$entity}}::class, function (Faker\Generator $faker) {
    return [
@foreach($fields as $field)
        '{{$field['name']}}' => {!! \Lemec93\Support\Generators\FactoryGenerator::getFactoryFieldsContent($field) !!},
@endforeach
    ];
});