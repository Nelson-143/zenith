<!-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh -->
<div class="nav-item dropdown">
    <a href="#" class="nav-link" data-bs-toggle="dropdown" aria-label="Show notifications">
        <lord-icon src="https://cdn.lordicon.com/lznlxwtc.json" trigger="hover" colors="primary:black" style="width:20px;height:20px"></lord-icon>
<span class="badge bg-red">{{ $notifications->where('read_at', null)->count() }}</span>
    <div class="dropdown-menu dropdown-menu-end">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Notifications</h3>
                <button class="btn btn-link" onclick="markAllAsRead()">Mark all as read</button>
            </div>
            <div class="list-group">
                @foreach ($notifications as $notification)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $notification->data['title'] }}</strong>
                                <p>{{ $notification->data['message'] }}</p>
                            </div>
                            <div>
                                <button onclick="markNotificationAsRead('{{ $notification->id }}')">Mark as read</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
