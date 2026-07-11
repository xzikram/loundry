<?php

namespace App\Livewire\Tenant\Expenses;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Tenant\Expense;
use App\Models\Tenant\ExpenseCategory;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.tenant')]
class ExpensePage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterMonth = '';
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $description = '';
    public $amount = 0;
    public ?int $categoryId = null;
    public string $expenseDate = '';

    public function mount() { $this->filterMonth = now()->format('Y-m'); $this->expenseDate = now()->format('Y-m-d'); }
    public function updatingSearch() { $this->resetPage(); }

    public function openCreate()
    {
        $this->reset(['description', 'amount', 'categoryId', 'editingId']);
        $this->expenseDate = now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'expenseDate' => 'required|date',
        ]);

        $data = [
            'description' => $this->description,
            'amount' => $this->amount,
            'category_id' => $this->categoryId,
            'expense_date' => $this->expenseDate,
            'created_by' => Auth::guard('tenant')->id(),
        ];

        if ($this->editingId) {
            Expense::findOrFail($this->editingId)->update($data);
        } else {
            Expense::create($data);
        }

        $this->showModal = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['description', 'amount', 'categoryId', 'editingId']);
        $this->resetValidation();
    }

    public function delete($id) { Expense::findOrFail($id)->delete(); }

    public function render()
    {
        $query = Expense::with('category')
            ->where('description', 'like', '%' . addcslashes($this->search, '%_') . '%');

        if ($this->filterMonth) {
            $query->whereYear('expense_date', substr($this->filterMonth, 0, 4))
                  ->whereMonth('expense_date', substr($this->filterMonth, 5, 2));
        }

        $expenses = $query->latest('expense_date')->paginate(15);
        $totalMonth = Expense::whereYear('expense_date', substr($this->filterMonth, 0, 4))
                        ->whereMonth('expense_date', substr($this->filterMonth, 5, 2))
                        ->sum('amount');
        $categories = ExpenseCategory::all();

        return view('livewire.tenant.expense-page', compact('expenses', 'totalMonth', 'categories'));
    }
}
