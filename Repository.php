<?php namespace App\Libs\Core;

use App\Exceptions\SaveFailureException;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ModelNotFoundException;

class Repository {

	/**
	 * @var Model
	 */
	protected $Model;
	protected $perPage;

	/**
	 * @param Model $Model
	 */
	public function __construct( Model $Model )
	{
		$this->setModel($Model);
	}

	/**
	 * @param $Model
	 */
	protected function setModel( $Model )
	{
		$this->Model = $Model;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	protected function newQuery()
	{
		if(is_null($this->Model)) {
			throw new \RuntimeException();
		}
		return $this->Model->newQuery();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function findAll()
	{
		return $this->newQuery()->get()->all();
	}

	/**
	 * @param $id
	 *
	 * @return Model
	 */
	public function findOrNew( $id )
	{
		$Model = $this->find($id);

		if(!$Model) {
			return $this->create();
		}

		return $Model;
	}

	/**
	 * @param $id
	 *
	 * @return Model
	 */
	public function findOrFail( $id )
	{
		$Model = $this->find($id);

		return $this->orFail($Model);
	}

	/**
	 * @param null|Model $Model
	 *
	 * @return Model
	 */
	protected function orFail( $Model )
	{
		if(!$Model) {
			throw (new ModelNotFoundException())->setModel($this->Model);
		}

		return $Model;
	}

	/**
	 * @param $id
	 *
	 * @return Model
	 */
	public function find( $id )
	{
		return $this->newQuery()->find($id);
	}

	/**
	 * @param int $page
	 *
	 * @return Model[]
	 */
	public function getPaged( $page = 1 )
	{
		$skip   = ($page - 1) * $this->perPage;
		$count  = $this->perPage;

		return $this->newQuery()->skip($skip)->take($count)->get();
	}

	/**
	 * @return mixed
	 */
	public function create()
	{
		$class_name = get_class($this->Model);
		return new $class_name;
	}

	/**
	 * @param Model $Model
	 * @param array      $input
	 * @param array      $options
	 *
	 * @return bool
	 */
	public function save( Model $Model, $input = [], $options = [] )
	{
		$Model->fill($input);
		return $Model->save($options);
	}

	/**
	 * @param Model $Model
	 * @param array      $input
	 * @param array      $options
	 *
	 * @return bool
	 */
	public function saveOrFail( Model $Model, $input = [], $options = [] )
	{
		$result = $this->save($Model, $input, $options);
		if($result) {
			return $result;
		}

		throw new SaveFailureException();
	}

	/**
	 * @param Model $Model
	 *
	 * @return bool|null
	 * @throws \Exception
	 */
	public function delete( Model $Model )
	{
		return $Model->delete();
	}
}
