<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Image;
use App\Models\Locality;
use App\Models\Property;
use App\Models\PropertyAdvice;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\Subscriber;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrationController extends Controller
{
    public function migrate()
    {
        $import = file_get_contents("properties.json", true);

        foreach (json_decode($import) as $request){
            if (!Property::find($request->ID)){
                $query = DB::table('a12_postmeta')->where('post_id', $request->ID);
                $data = new Property();
                $data->id = $request->ID;
                $data->user_id = $request->post_author;
                $data->title = $request->post_title;
                $data->slug = $request->post_name;
                $price = $query->where('meta_key',"real_estate_property_price")->select('meta_value')->get()[0]->meta_value;
                if ($price){
                    $data->price = $price;
                }
                $data->description = $request->post_content;

                $query2 = $query->where('meta_key','_yoast_wpseo_primary_property-status')->get();
                if ($query2->count() > 0){
                    $query2 = $query2[0]->meta_value;
                    if ($query2){
                        $data->property_status_id = PropertyStatus::where('name', DB::table('a12_terms')->where('term_id', $query2)->get()[0]->name)->first()->id;

                    }
                }

                $query3 = $query->where('meta_key','_yoast_wpseo_primary_property-type')->get();
                if ($query3->count() > 0){
                    $query3 = $query2[0]->meta_value;
                    if ($query3){
                        $data->property_type_id = PropertyType::where('name', DB::table('a12_terms')->where('term_id', $query3)->get()[0]->name)->first()->id;
                    }
                }

                $image = DB::table('a12_postmeta')->where('post_id', $request->ID)->where('meta_key',"real_estate_property_images")->first();
                if ($image && $image->meta_value){
                    $image1 = DB::table('a12_postmeta')->where('post_id', $image->meta_value)->first();
                    if ($image1){
                        $datai = new Image();
                        $datai->category = "migrated";
                        $datai->path= $image1->meta_value;
                        $datai->user_id= $request->post_author;
                        $datai->save();
                        $data->image_id = $datai->id;
                    }

                }

                $query4 = $query->where('meta_key',"real_estate_property_bedrooms")->first();
                if ($query4){
                    $data->bedrooms = $query4->meta_value;
                }

                $query5 = $query->where('meta_key',"real_estate_property_bathrooms")->first();
                if ($query5){
                    $data->bathrooms = $query5->meta_value;
                }

                $query6 = $query->where('meta_key',"real_estate_property_size")->first();
                if ($query5){
                    $data->total_area = $query6->meta_value;
                }

                $query7 = $query->where('meta_key',"real_estate_property_land")->first();
                if ($query7){
                    $data->covered_area = $query7->meta_value;
                }

                $query8 = $query->where('meta_key',"real_estate_property_address")->first();
                if ($query8){
                    $data->address = $query8->meta_value;
                }



                $query9 = DB::table('a12_term_relationships')->where('object_id', $request->ID)->get();


                if ($query9->count() > 0){
                    $tid = $query9[$query9->count() - 1]->term_taxonomy_id;

                    $name = DB::table('a12_terms')->where('term_id', $tid)->first()->name;


                    $lid = Locality::where('name', 'LIKE', '%'.$name.'%')->first();
                    if ($lid){
                        $data->locality_id = $lid->id;
                    }
                }

                $data->save();
            }

        }

        return "done!";
    }

    public function articles()
    {
        $import = file_get_contents("articles.json", true);

        foreach (json_decode($import) as $request){
            if (!Article::find($request->ID)){
                $query = DB::table('a12_postmeta')->where('post_id',$request->ID)->get();
                $data = new Article();
                $data->id = $request->ID;
                $data->user_id = $request->post_author;
                $data->title = $request->post_title;
                $data->slug = $request->post_name;
                $data->text = $request->post_content;


                $query9 = DB::table('a12_term_relationships')->where('object_id', $request->ID)->get();


                if ($query9->count() > 0){
                    $tid = $query9[0]->term_taxonomy_id;

                    $name = DB::table('a12_terms')->where('term_id', $tid)->first()->name;


                    $lid = PropertyAdvice::where('name', 'LIKE', '%'.$name.'%')->first();
                    if ($lid){
                        $data->category_id = $lid->id;
                    }else{
                        $data->category_id = 4;

                    }
                }

                $data->save();
            }

        }

        return "done!";
    }


    public function agents()
    {
        $users = User::all();

        foreach ($users as $user){
            $prop = Property::where('user_id', $user->id)->first();
            if ($prop){
                $user->role_id = 2;
                $user->save();
            }
        }
        return "done";
    }

    public function users()
    {
        $data = file_get_contents("users.json", true);
        foreach (json_decode($data) as $d){
            if (!User::find($d->ID)){
                $user = new User();
                $user->id= $d->ID;
                $user->username= $d->user_nicename;
                $user->name= $d->display_name;
                $user->email= $d->user_email;
                $user->password= bcrypt("pass@@@wor@rD");
                $user->role_id= 1;
                $user->remember_token= Str::random(100);
                $user->created_at= new Carbon($d->user_registered);
                $user->save();
            }

        }

        return "done";
    }

    public function sub()
    {
        $data = file_get_contents("subscribers.json", true);
        foreach (json_decode($data) as $d){
            $sub= new Subscriber();
            $sub->email= $d->email;
            $sub->save();
        }

        return "done";
    }
}


