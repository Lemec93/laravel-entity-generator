use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Lemec93\Support\Traits\MigrationTrait;

class Create{{$class}}Table extends Migration
{
    use MigrationTrait;

    public function up()
    {
        DB::beginTransaction();

        $this->createTable();
@foreach($relations['belongsToMany'] as $relation)

        $this->createBridgeTable('{{$entity}}', '{{$relation}}');
@endforeach
@foreach($relations['belongsTo'] as $relation)

        $this->addForeignKey('{{$entity}}', '{{$relation}}');
@endforeach
@foreach($relations['hasOne'] as $relation)

        $this->addForeignKey('{{$relation}}', '{{$entity}}', true);
@endforeach
@foreach($relations['hasMany'] as $relation)

        $this->addForeignKey('{{$relation}}', '{{$entity}}', true);
@endforeach

        DB::commit();
    }

    public function down()
    {
        DB::beginTransaction();

@foreach($relations['hasOne'] as $relation)
        $this->dropForeignKey('{{$relation}}', '{{$entity}}', true);
@endforeach
@foreach($relations['hasMany'] as $relation)
        $this->dropForeignKey('{{$relation}}', '{{$entity}}', true);
@endforeach
@foreach($relations['belongsToMany'] as $relation)
        $this->dropBridgeTable('{{$entity}}', '{{$relation}}');
@endforeach
        Schema::drop('{{\Illuminate\Support\Str::plural(\Illuminate\Support\Str::snake($entity))}}');

        DB::commit();
    }

    public function createTable()
    {
        Schema::create('{{\Illuminate\Support\Str::plural(\Illuminate\Support\Str::snake($entity))}}', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
@foreach ($table as $row )
            {!!$row!!}
@endforeach
        });
    }
}
