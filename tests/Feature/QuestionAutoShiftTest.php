<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QuestionAutoShiftTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::first() ?? User::factory()->create([
            'email' => 'admin@test.com',
        ]);
    }

    public function test_inserting_question_at_existing_order_shifts_display_order_while_codes_remain_immutable(): void
    {
        $category = Category::where('code', 'KRS')->first() ?? Category::first();

        // Get question currently at order 21
        $qAt21Before = Question::where('order', 21)->first();
        $this->assertNotNull($qAt21Before);
        $originalCode21 = $qAt21Before->code;

        $countBefore = Question::count();

        // Post new question at order 21 with a unique code 'H99'
        $response = $this->actingAs($this->admin)->post(route('admin.questions.store'), [
            'category_id' => $category->id,
            'code' => 'H99',
            'order' => 21,
            'text' => 'Butir uji auto shift order 21',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.questions.index'));
        $response->assertSessionHas('success');

        // Total questions increased by 1
        $this->assertEquals($countBefore + 1, Question::count());

        // Check the newly inserted question at order 21
        $newQuestion = Question::where('order', 21)->first();
        $this->assertNotNull($newQuestion);
        $this->assertEquals('H99', $newQuestion->code);
        $this->assertEquals('Butir uji auto shift order 21', $newQuestion->text);

        // Check that the previous question at order 21 is now at order 22, and its code is UNCHANGED
        $shiftedQ22 = Question::find($qAt21Before->id);
        $this->assertEquals(22, $shiftedQ22->order);
        $this->assertEquals($originalCode21, $shiftedQ22->code);
    }

    public function test_inserting_question_with_empty_code_auto_generates_unique_code(): void
    {
        $category = Category::first();
        $nextOrder = (int) (Question::max('order') ?? 0) + 1;

        $response = $this->actingAs($this->admin)->post(route('admin.questions.store'), [
            'category_id' => $category->id,
            'code' => '',
            'order' => $nextOrder,
            'text' => 'Butir baru dengan kode auto generate',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.questions.index'));
        $response->assertSessionHas('success');

        $created = Question::where('order', $nextOrder)->first();
        $this->assertNotNull($created);
        $this->assertMatchesRegularExpression('/^H\d+$/', $created->code);
    }
}
