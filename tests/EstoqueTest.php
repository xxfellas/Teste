<?php

use PHPUnit\Framework\TestCase;
use App\Estoque;


class EstoqueTest extends TestCase
{
    private Estoque $estoque;

    protected function setUp(): void
    {
        $this->estoque = new Estoque();
    }

    public function testAdicionarProduto()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 10,
            'preco' => 5.0
        ];

        $this->estoque->adicionarProduto($produto);

        $produtos = $this->estoque->listarProdutos();

        $this->assertArrayHasKey('Produto 1', $produtos);
        $this->assertEquals(50.0, $produtos['Produto 1']['valor_total']);
    }

    public function testAdicionarProdutoJaExistente()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 10,
            'preco' => 5.0
        ];

        $this->estoque->adicionarProduto($produto);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Produto já existente no estoque.');

        $this->estoque->adicionarProduto($produto);
    }

    public function testAdicionarProdutoComQuantidadeInvalida()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 0,
            'preco' => 5.0
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Quantidade inválida para o produto.');

        $this->estoque->adicionarProduto($produto);
    }

    public function testAdicionarProdutoComPrecoInvalido()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 10,
            'preco' => 0.0
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Preço inválido para o produto.');

        $this->estoque->adicionarProduto($produto);
    }

    public function testConsultarProduto()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 10,
            'preco' => 5.0
        ];

        $this->estoque->adicionarProduto($produto);

        $produtoConsultado = $this->estoque->consultarProduto('Produto 1');

        $this->assertEquals($produto['nome'], $produtoConsultado['nome']);
        $this->assertEquals($produto['quantidade'], $produtoConsultado['quantidade']);
    }

    public function testConsultarProdutoInexistente()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Produto não encontrado no estoque.');

        $this->estoque->consultarProduto('Produto Inexistente');
    }

    public function testRemoverProduto()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 10,
            'preco' => 5.0
        ];

        $this->estoque->adicionarProduto($produto);
        $this->estoque->removerProduto('Produto 1');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Produto não encontrado no estoque.');

        $this->estoque->consultarProduto('Produto 1');
    }

    public function testAtualizarQuantidade()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 10,
            'preco' => 5.0
        ];

        $this->estoque->adicionarProduto($produto);
        $this->estoque->atualizarQuantidade('Produto 1', 5);

        $produtoAtualizado = $this->estoque->consultarProduto('Produto 1');

        $this->assertEquals(15, $produtoAtualizado['quantidade']);
        $this->assertEquals(75.0, $produtoAtualizado['valor_total']);
    }

    public function testAtualizarQuantidadeParaZero()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 10,
            'preco' => 5.0
        ];

        $this->estoque->adicionarProduto($produto);
        $this->estoque->atualizarQuantidade('Produto 1', -10);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Produto não encontrado no estoque.');

        $this->estoque->consultarProduto('Produto 1');
    }

    public function testAtualizarQuantidadeParaValorInvalido()
    {
        $produto = [
            'nome' => 'Produto 1',
            'quantidade' => 10,
            'preco' => 5.0
        ];

        $this->estoque->adicionarProduto($produto);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Quantidade inválida para atualização.');

        $this->estoque->atualizarQuantidade('Produto 1', -15);
    }
}
