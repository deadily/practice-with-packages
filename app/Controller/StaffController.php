<?php

namespace Controller;

use Model\Building;
use Model\Room;
use Model\RoomType;
use Src\View;
use Src\Request;

class StaffController
{
    public function staff_buildings(): string
    {
        if (!app()->auth::check() || app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login'); 
        }

        $buildings = Building::all(); 
        
        $totalAreaAll = 0;
        $totalSeatsAll = 0;

        foreach ($buildings as $building) {
            $totalAreaAll += (float)($building->area ?? 0);
            $totalSeatsAll += (int)($building->seats ?? 0);
        }
        
        return new View('site.staff_buildings', [
            'buildings' => $buildings,
            'totalAreaAll' => $totalAreaAll,
            'totalSeatsAll' => $totalSeatsAll
        ]);
    }

    public function staff_rooms(): string
    {
        if (!app()->auth::check() || app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        $buildingId = $_GET['building_id'] ?? null;

        if ($buildingId) {
            $rooms = Room::where('building_id', $buildingId)->get();
        } else {
            $rooms = Room::all();
        }

        $buildings = Building::all();
        $roomTypes = RoomType::all();

        return new View('site.staff_rooms', [
            'rooms' => $rooms,
            'buildings' => $buildings,
            'roomTypes' => $roomTypes,
            'buildingId' => $buildingId
        ]);
    }

    public function create_building(): string
    {
        if (!app()->auth::check() || app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }
        
        return new View('site.add_building');
    }

    public function store_building(Request $request): void
    {
        if (!app()->auth::check() || app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (empty($name) || empty($address)) {
            app()->route->redirect('/add_building');
            return;
        }

        $building = new Building();
        $building->name = $name;
        $building->address = $address;

        try {
            $building->save();
            app()->route->redirect('/staff_buildings');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            app()->route->redirect('/add_building');
        }
    }

    public function staff_delete_building(Request $request): void
    {
        if (!app()->auth::check() || app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        if ($request->method === 'POST') {
            $id = $request->get('id');
            $building = Building::find($id);
            
            if ($building) {
                $building->delete();
            }
        }

        app()->route->redirect('/staff_buildings'); 
    }

    public function create_room(): string
    {
        if (!app()->auth::check() || app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        $buildings = \Model\Building::all();
        $selectedBuildingId = $_GET['building_id'] ?? null;
        $roomTypes = \Model\RoomType::all();

        return new View('site.add_room', [
            'buildings' => $buildings,
            'selectedBuildingId' => $selectedBuildingId,
            'roomTypes' => $roomTypes
        ]);
    }

    public function store_room(Request $request): void
    {
        if (!app()->auth::check() || app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        $buildingId = (int)($_POST['building_id'] ?? 0);
        $roomNumber = trim($_POST['room_number'] ?? '');
        $roomTypeId = (int)($_POST['room_type_id'] ?? 0);
        $area = (float)($_POST['area'] ?? 0);
        $seatTotal = (int)($_POST['seat_total'] ?? 0);

        if ($buildingId <= 0 || empty($roomNumber)) {
            app()->route->redirect('/add_room');
            return;
        }

        $room = new Room();
        $room->building_id = $buildingId;
        $room->room_number = $roomNumber;
        $room->room_type_id = $roomTypeId;
        $room->area = $area;
        $room->seat_total = $seatTotal;

        try {
            $room->save();

            $building = Building::find($buildingId);
            if ($building) {
                $building->recalculateStats();
            }

            $redirectUrl = '/staff_rooms';
            if ($buildingId > 0) {
                $redirectUrl .= '?building_id=' . $buildingId;
            }
            app()->route->redirect($redirectUrl);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            app()->route->redirect('/add_room');
        }
    }

    public function staff_delete_room(Request $request): void
    {
        if (!app()->auth::check() || app()->auth::user()->isAdmin()) {
            app()->route->redirect('/login');
        }

        if ($request->method === 'POST') {
            $id = (int)$request->get('id');
            $buildingId = (int)$request->get('building_id');

            $room = Room::find($id);
            if ($room) {
                $buildingIdFromRoom = $room->building_id;
                $room->delete();

                $building = Building::find($buildingIdFromRoom);
                if ($building) {
                    $building->recalculateStats();
                }
            }

            $redirectUrl = '/staff_rooms';
            if ($buildingId > 0) {
                $redirectUrl .= '?building_id=' . $buildingId;
            } elseif ($buildingIdFromRoom ?? 0 > 0) {
                $redirectUrl .= '?building_id=' . $buildingIdFromRoom;
            }
            app()->route->redirect($redirectUrl);
        }

        app()->route->redirect('/staff_rooms');
    }
}