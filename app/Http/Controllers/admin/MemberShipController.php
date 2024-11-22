<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MemberShipModel;
use Illuminate\Http\Request;

class MemberShipController extends Controller
{
    public function index()
    {
        $titlePage = 'Cài đặt chung';
        $page_menu = 'membership';
        $page_sub = null;
        $data = MemberShipModel::get();

        return view('admin.membership.index',compact('titlePage','page_menu','page_sub','data'));
    }

    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'memberships.*.id' => 'required|exists:memberships,id',
            'memberships.*.name' => 'required|string|max:255',
            'memberships.*.discount_percent' => 'required|numeric|min:0|max:100',
        ]);

        $membershipsData = $validatedData['memberships'];

        foreach ($membershipsData as $membershipData) {
            $membership = MemberShipModel::find($membershipData['id']);

            if ($membership) {
                $membership->name = $membershipData['name'];
                $membership->discount_percent = $membershipData['discount_percent'];
                $membership->save();
            }
        }

        return redirect()->back()->with('success', 'Memberships updated successfully.');
    }
}
