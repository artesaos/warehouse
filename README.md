# Artesãos Warehouse 

Laravel 5 - Warehouse to abstract the database layer with Repositories pattern

## Table of Contents

- <a href="#installation">Installation</a>
    - <a href="#composer">Composer</a>
    - <a href="#laravel">Laravel</a>
- <a href="#methods">Methods</a>
    - <a href="#artesaoswarehousecontractsrepositoryinterface">RepositoryInterface</a>
    - <a href="#artesaoswarehousecontractsrepositorycriteriainterface">RepositoryCriteriaInterface</a>
    - <a href="#artesaoswarehousecontractspresenterinterface">PresenterInterface</a>
    - <a href="#artesaoswarehousecontractscriteriainterface">CriteriaInterface</a>
- <a href="#usage">Usage</a>
	- <a href="#create-a-model">Create a Model</a>
	- <a href="#create-a-repository">Create a Repository</a>
	- <a href="#use-methods">Use methods</a>
	- <a href="#create-a-criteria">Create a Criteria</a>
	- <a href="#using-the-criteria-in-a-controller">Using the Criteria in a Controller</a>
	- <a href="#using-the-requestcriteria">Using the RequestCriteria</a>
- <a href="#presenters">Presenters</a>
    - <a href="#fractal-presenter">Fractal Presenter</a>
        - <a href="#create-a-presenter">Create a Fractal Presenter</a>
    - <a href="#enabling-in-your-repository-1">Enabling in your Repository</a>

## Installation

### Composer

Add `artesaos/warehouse` to the "require" section of your `composer.json` file.

```json
	"artesaos/warehouse": "dev-master"
```

Run `composer update` to get the latest version of the package.

### Laravel

In your `config/app.php` add `'Artesaos\Warehouse\Providers\WarehouseServiceProvider'` to the end of the `providers` array:

```php
'providers' => array(
    ...,
    'Illuminate\Workbench\WorkbenchServiceProvider',
    'Artesaos\Warehouse\Providers\WarehouseServiceProvider',
),
```

Publish Configuration

```shell
php artisan vendor:publish --provider="Artesaos\Warehouse\Providers\WarehouseServiceProvider"
```

## Methods

### Artesaos\Warehouse\Contracts\RepositoryInterface

- all($columns = array('*'))
- paginate($limit = null, $columns = ['*'])
- find($id, $columns = ['*'])
- findByField($field, $value, $columns = ['*'])
- create(array $attributes)
- update(array $attributes, $id)
- delete($id)
- with(array $relations);
- getFieldsSearchable();


### Artesaos\Warehouse\Contracts\RepositoryCriteriaInterface

- pushCriteria(CriteriaInterface $criteria)
- getCriteria()
- getByCriteria(CriteriaInterface $criteria)
- skipCriteria($status = true)
- getFieldsSearchable()

### Artesaos\Warehouse\Contracts\PresenterInterface

- present($data);

### Artesaos\Warehouse\Contracts\CriteriaInterface

- apply($model, RepositoryInterface $repository);

## Usage

### Create a Model

Create your model normally, but it is important to define the attributes that can be filled from the input form data.

```php
namespace App;

class Post extends Eloquent { // or Ardent, Or any other Model Class

    protected $fillable = [
        'title',
        'author',
        ...
     ];

     ...
}
```

### Create a Repository

```php
namespace App;

use Artesaos\Warehouse\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Post";
    }
}
```

### Use methods

```php
namespace App\Http\Controllers;

use App\PostRepository;

class PostsController extends BaseController {

    /**
     * @var PostRepository
     */
    protected $repository;

    public function __construct(PostRepository $repository){
        $this->repository = $repository;
    }
    
    ....
}
```

Find all results in Repository

```php
$posts = $this->repository->all();
```

Find all results in Repository with pagination

```php
$posts = $this->repository->paginate($limit = null, $columns = ['*']);
```

Find by result by id

```php
$post = $this->repository->find($id);
```

Find by result by field name

```php
$posts = $this->repository->findByField('country_id','15');
```

Create new entry in Repository

```php
$post = $this->repository->create( Input::all() );
```

Update entry in Repository

```php
$post = $this->repository->update( Input::all(), $id );
```

Delete entry in Repository

```php
$this->repository->delete($id)
```

### Create a Criteria

Criteria is a way to change the repository of the query by applying specific conditions according to their need. You can add multiple Criteria in your repository

```php

use Artesaos\Warehouse\Contracts\RepositoryInterface; 
use Artesaos\Warehouse\Contracts\CriteriaInterface;

class MyCriteria implements CriteriaInterface {

    public function apply($model, RepositoryInterface $repository)
    {
        $query = $query->where('user_id','=', Auth::user()->id );
        return $query;
    }
}
```

### Using the Criteria in a Controller

```php

namespace App\Http\Controllers;
use App\PostRepository;

class PostsController extends BaseController {

    /**
     * @var PostRepository
     */
    protected $repository;

    public function __construct(PostRepository $repository){
        $this->repository = $repository;
    }


    public function index()
    {
        $this->repository->pushCriteria(new MyCriteria());
        $posts = $this->repository->all();
		...
    }

}
```

Getting results from Criteria

```php
$posts = $this->repository->getByCriteria(new MyCriteria());
```

Setting the default Criteria in Repository

```php
use Artesaos\Warehouse\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {
   
    public function boot(){
        $this->pushCriteria(new MyCriteria());
        $this->pushCriteria(new AnotherCriteria());
        ...
    }
    
    function model(){
       return "App\\Post";
    }
}
```

### Skip criteria defined in the repository

Use *skipCriteria* before any method in the repository

```php

$posts = $this->repository->skipCriteria()->all();

```


### Using the RequestCriteria

RequestCriteria is a standard Criteria implementation. It enables filters to perform in the repository from parameters sent in the request.

You can perform a dynamic search, filter the data and customize the queries

To use the Criteria in your repository, you can add a new criteria in the boot method of your repository, or directly use in your controller, in order to filter out only a few requests

####Enabling in your Repository

```php
use Artesaos\Warehouse\Eloquent\BaseRepository;
use Artesaos\Warehouse\Criteria\RequestCriteria;


class PostRepository extends BaseRepository {

	/**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email'
    ];

    public function boot(){
        $this->pushCriteria(app('Artesaos\Warehouse\Criteria\RequestCriteria'));
        ...
    }
    
    function model(){
       return "App\\Post";
    }
}
```

Remember, you need to define which fields from the model can be searchable.

In your repository set **$fieldSearchable** with the name of the fields to be searchable.

```php
protected $fieldSearchable = [
	'name',
	'email'
];
```

You can set the type of condition which will be used to perform the query, the default condition is "**=**"

```php
protected $fieldSearchable = [
	'name'=>'like',
	'email', // Default Condition "="
	'your_field'=>'condition'
];
```

####Enabling in your Controller

```php
	public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $posts = $this->repository->all();
		...
    }
```

#### Example the Criteria

Request all data without filter by request

*http://artesaos.local/users*

```json
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum",
        "email": "lorem@ipsum.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
    {
        "id": 3,
        "name": "Laravel",
        "email": "laravel@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    }
]
```

Conducting research in the repository

*http://artesaos.local/users?search=John%20Doe*

or

*http://artesaos.local/users?search=John&searchFields=name:like*

or

*http://artesaos.local/users?search=john@gmail.com&searchFields=email:=*

```json
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    }
]
```

Filtering fields

*http://artesaos.local/users?filter=id;name*

```json
[
    {
        "id": 1,
        "name": "John Doe"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 3,
        "name": "Laravel"
    }
]
```

Sorting the results

*http://artesaos.local/users?filter=id;name&orderBy=id&sortedBy=desc*

```json
[
    {
        "id": 3,
        "name": "Laravel"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 1,
        "name": "John Doe"
    }
]
```

####Overwrite params name

You can change the name of the parameters in the configuration file **config/warehouse.php**

### Presenters

Presenter to wrap and render objects.

#### Fractal Presenter

##### Create a Transformer Class

```php

use App\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            'id'      => (int) $post->id,
            'title'   => $post->title,
            'content' => $post->content
        ];
    }
}
```

##### Create a Presenter

```php
use Artesaos\Warehouse\Presenter\FractalPresenter;

class PostPresenter extends FractalPresenter {

    /**
     * Prepare data to present
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PostTransformer();
    }
}
```

##### Enabling in your Repository

```php
use Artesaos\Warehouse\Eloquent\BaseRepository;
use Artesaos\Warehouse\Criteria\RequestCriteria;

class PostRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model(){
       return "App\\Post";
    }
    
    /**
     * Specify Presenter class name
     *
     * @return mixed
     */
    public function presenter()
    {
        return "App\\Presenter\\PostPresenter";
    }
}
```