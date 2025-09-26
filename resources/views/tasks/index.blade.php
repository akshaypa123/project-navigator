@extends('layouts.app')
@section('content')    

<div class="flex items-center justify-between border rounded p-2">
<div>
<input type="checkbox" :checked="s.is_completed" @change="toggleSubtaskCompletion(s)" />
<span x-text="s.title" class="ml-2"></span>
</div>
<div>
<button @click="deleteSubtask(s)" class="text-red-600 text-sm">Delete</button>
</div>
</div>
</template>
</div>
<div class="mt-3 flex space-x-2">
<input x-model="newSubtaskTitle" placeholder="Subtask title" class="border p-2 flex-1" />
<button @click="addSubtask()" class="bg-green-600 text-white px-3 py-2 rounded">Add Subtask</button>
</div>
</div>


<hr class="my-3" />


<!-- Timelogs -->
<div>
<h3 class="font-semibold">Timelogs</h3>
<div class="mt-2 space-y-2">
<template x-for="t in detail.timelogs" :key="t.id">
<div class="border p-2 rounded">
<p class="text-sm"><strong x-text="t.user.name"></strong> — <span x-text="t.hours_worked"></span> hrs on <span x-text="t.log_date"></span></p>
<p class="text-xs text-gray-600" x-text="t.description"></p>
</div>
</template>
</div>


<div class="mt-3 grid grid-cols-3 gap-2">
<input type="datetime-local" x-model="timelog.log_date" class="border p-2 col-span-1" />
<input type="number" step="0.25" x-model="timelog.hours_worked" class="border p-2 col-span-1" placeholder="Hours" />
<input x-model="timelog.description" placeholder="Description" class="border p-2 col-span-1" />
</div>
<div class="mt-2">
<button @click="addTimelog()" class="bg-blue-600 text-white px-4 py-2 rounded">Add Timelog</button>
</div>
</div>


<hr class="my-3" />


<!-- Task Logs -->
<div>
<h3 class="font-semibold">Task Log History</h3>
<div class="mt-2 space-y-1 text-xs text-gray-600">
<template x-for="l in detail.logs" :key="l.id">
<div>
<strong x-text="l.user.name"></strong> — <span x-text="l.action"></span> — <span x-text="new Date(l.created_at).toLocaleString()"></span>
</div>
</template>
</div>
</div>


<div class="mt-4 text-right">
<button @click="closeDetail()" class="px-4 py-2">Close</button>
</div>
</div>
</div>
</div>
<script>
function taskDetail(taskId) {
    return {
        detail: { id: taskId, timelogs: [], logs: [], subtasks: [] },
        timelog: { log_date: '', hours_worked: '', description: '' },
        newSubtaskTitle: '',
        openDetailModal: true,
        openAddModal: false,

        async init() {
            if (this.detail.id) {
                await this.refreshDetail(this.detail.id);
            }
        },

        async refreshDetail(taskId) {
            const res = await fetch(`/api/tasks/${taskId}`);
            if (!res.ok) return;
            const data = await res.json();
            this.detail = data;
        },

        closeDetail() {
            this.openDetailModal = false;
            this.detail = { subtasks: [], timelogs: [], logs: [] };
        },

        async addSubtask() {
            if (!this.newSubtaskTitle.trim()) { alert('Enter subtask title'); return; }
            const res = await fetch('/api/subtasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ task_id: this.detail.id, title: this.newSubtaskTitle })
            });
            const data = await res.json();
            alert(data.message || 'Subtask added');
            this.newSubtaskTitle = '';
            await this.refreshDetail(this.detail.id);
            this.fetchSubtasks();
        },

        async toggleSubtaskCompletion(s) {
            const res = await fetch(`/api/subtasks/${s.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ is_completed: !s.is_completed, title: s.title })
            });
            const data = await res.json();
            await this.refreshDetail(this.detail.id);
            this.fetchSubtasks();
        },

        async deleteSubtask(s) {
            if (!confirm('Delete subtask?')) return;
            const res = await fetch(`/api/subtasks/${s.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await res.json();
            alert(data.message || 'Deleted');
            await this.refreshDetail(this.detail.id);
            this.fetchSubtasks();
        },

        async addTimelog() {
            if (!this.timelog.log_date || !this.timelog.hours_worked) {
                alert('Enter date and hours');
                return;
            }
            const res = await fetch('/api/timelogs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    task_id: this.detail.id,
                    log_date: this.timelog.log_date,
                    hours_worked: this.timelog.hours_worked,
                    description: this.timelog.description
                })
            });

            const data = await res.json();
            alert(data.message || 'Timelog added');
            this.timelog = { log_date: '', hours_worked: '', description: '' };
            await this.refreshDetail(this.detail.id);
        },

        editSubtask(sub) {
            this.openAddModal = false;
            alert('Editing subtask not implemented in UI — implement accordingly.');
        }
    }
}
</script>

@endsection