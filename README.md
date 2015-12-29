# Artesãos Warehouse V2 - A simple and direct approach to repositories!

[![Total Downloads](https://poser.pugx.org/artesaos/warehouse/downloads.svg)](https://packagist.org/packages/artesaos/warehouse)
[![Latest Stable Version](https://poser.pugx.org/artesaos/warehouse/v/stable.svg)](https://packagist.org/packages/artesaos/warehouse)
[![Latest Unstable Version](https://poser.pugx.org/artesaos/warehouse/v/unstable.svg)](https://packagist.org/packages/artesaos/warehouse)
[![License](https://poser.pugx.org/artesaos/warehouse/license.svg)](https://packagist.org/packages/artesaos/warehouse)

## O que é Warehouse V2?

Warehouse v2 é um pacote um pouco átipico, já que você pode usa-lo sem precisar baixar ele. Ele se classificária melhor como uma demosntração pronta para o uso.

Muito se fala sobre o padrão de projeto Repository, básicamente ele é uma camada a mais entre sua aplicação e o banco de dados, no caso do Laravel sendo responsável direto por agir sobre seus models e queries.

### O que é *Repository Pattern* ?

Básicamente é a camada onde você executa seus comandos no banco de dados.

Há muita filosofia por trás desse modelo. No seu modo mais puro os métodos de um repositório nao retornam objetos  complexos ou que possuam alguma dependencia, retornam arrays ou objetos simples (*StdClass*). Isso por que um de seus objetivos é permitir a troca de um repositório que trabalha com MySQL por exemplo, por um que trabalhe com MongoDB.

#### O mundo real hoje

OK! Tudo muito lindo, no papel. Nao são todos os projetos que precisam de uma abordagem assim, se você usa o Laravel abrir mão do Eloquent não é algo que todos cojitem fazer. E trocar de banco de dados no Laravel não é uma tarefa tão complexa, graças ao Eloquent.

> Devido a facilidade e praticidade que o Eloquent e Collections trazem, este pacote nao retorna objetos planos e sim objetos Eloquent e Collections.

Muitos não sabem e outros se esquecem que outro objetivo de um repositório é organizar e centralizar suas consultas e até mesmo regras de negócio. Essa é a principal abordagem que o Warehouse V2 pretende suprir.

-----------------

## Instalando

Execute `composer require artesaos/warehouse 2.x-dev`

No arquivo `config/app.php` adcione o service provider `Artesaos\Warehouse\WarehouseServiceProvider`

```php
'providers' => [
    // ...
    Artesaos\Warehouse\Providers\WarehouseServiceProvider::class,
    // ...
],
```

> Este processo não é obrigatório. Você só precisa fazer isso caso esteja usando o Fractal.