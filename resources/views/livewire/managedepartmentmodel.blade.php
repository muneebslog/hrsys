<?php

use Livewire\Volt\Component;
use App\Models\Department;
use Livewire\Attributes\Validate;
Use Flux\Flux;

new class extends Component {
    public ?Department $department = null;
    
    #[Validate('required|string|max:255')]
    public $name = '';
    
    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|string|max:255')]
    public $prefix = '';
    
    public function mount($department = null)
    {
        if ($department != null) {
            $department = Department::find($department);
        }
        $this->department = $department;
        
        if ($department) {
            $this->name = $department->name;
            $this->description = $department->description;        }
    }

    public function save()
    {
        $this->validate();

        if ($this->department) {
            // Update existing department
            $this->department->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            
            session()->flash('message', 'Department updated successfully.');
            // $this->dispatch('department-updated', departmentId: $this->department->id);
        } else {
            // Create new department
            $department = Department::create([
                'name' => $this->name,
                'description' => $this->description,
                'prefix' => $this->prefix,
            ]);
            
            session()->flash('message', 'Department created successfully.');
            // $this->dispatch('department-created', departmentId: $department->id);
        }

        $this->dispatch('department-saved');
        Flux::modal('department-modal')->close();
        $this->reset(['name', 'description']);
    }

  
}; ?>

<div>
    <flux:modal.trigger name="department-modal">
        <flux:button size="sm" class="mt-1" tooltip="{{ $department ? 'Edit Department' : 'Add Department' }}" >{{ $department ? 'Edit' : '+' }}</flux:button>
    </flux:modal.trigger>

    <flux:modal name="department-modal" class="md:w-96">
        <form wire:submit="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $department ? 'Edit Department' : 'Add Department' }}</flux:heading>
                    <flux:text class="mt-2">{{ $department ? 'Update department details.' : 'Create a new department.' }}</flux:text>
                </div>

                <flux:input 
                    wire:model="name" 
                    label="Department Name" 
                    placeholder="Enter department name" 
                    required 
                />

                <flux:input 
                    wire:model="prefix" 
                    label="Department Prefix" 
                    placeholder="Enter department prefix" 
                    required 
                />

            
                <flux:textarea 
                    wire:model="description" 
                    label="Description" 
                    placeholder="Enter department description"
                    rows="3"
                />
              
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ $department ? 'Update' : 'Create' }}</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
