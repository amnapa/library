<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function a_book_can_be_added()
    {
        //$this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'new book',
            'author' => 'Victor'
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function a_title_is_required()
    {
        //$this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => '',
            'author' => 'Victor'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function an_author_is_required()
    {
        //$this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'My Book',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'new book',
            'author' => 'Victor'
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'new title',
            'author' => 'new author'
        ]);

        $this->assertEquals('new title', Book::first()->title);
        $this->assertEquals('new author', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->post('/books', [
            'title' => 'new book',
            'author' => 'Victor'
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }
}
