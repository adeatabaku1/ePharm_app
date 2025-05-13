namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreNotificationRequest;
use App\Http\Resources\Doctor\NotificationResource;
use App\Services\Doctor\NotificationService;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
protected $notificationService;

public function __construct(NotificationService $notificationService)
{
$this->notificationService = $notificationService;
}

public function index(Request $request)
{
$notifications = $this->notificationService->getAll($request->user()->id);
return NotificationResource::collection($notifications);
}

public function store(StoreNotificationRequest $request)
{
$notification = $this->notificationService->create($request->validated());
return new NotificationResource($notification);
}

public function show($id)
{
$notification = $this->notificationService->getById($id);
return new NotificationResource($notification);
}

public function markAsRead($id)
{
$this->notificationService->markAsRead($id);
return response()->json(['message' => 'Notification marked as read']);
}

public function destroy($id)
{
$this->notificationService->delete($id);
return response()->json(['message' => 'Notification deleted']);
}
}
