<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Bahawalpur' => ['Bahawalpur City', 'Bahawalpur Saddar', 'Ahmadpur East', 'Hasilpur', 'Yazman', 'Khairpur Tamewali'],
            'Rajanpur' => ['Rajanpur', 'Jampur', 'Rojhan'],
            'Dera Ghazi Khan' => ['Dera Ghazi Khan', 'Kot Chutta'],
            'Rahim Yar Khan' => ['Rahim Yar Khan', 'Sadiqabad', 'Liaqatpur', 'Khanpur'],
            'Bahawalnagar' => ['Bahawalnagar', 'Fortabbas', 'Chishtian', 'Haroonabad', 'Minchinabad'],
            'Muzaffargarh' => ['Muzaffargarh', 'Alipur', 'Jatoi'],
            'Bhakkar' => ['Bhakkar', 'Darya Khan', 'Mankera', 'Kalur Kot'],
            'Attock' => ['Attock', 'Hassanabdal', 'Fateh Jang', 'Jand', 'Pindi Gheb', 'Hazro'],
            'Chakwal' => ['Chakwal', 'Choa Saidan Shah', 'Kallar Kahar'],
            'Khushab' => ['Khushab', 'Noorpur', 'Quaidabad', 'Naushera'],
            'Layyah' => ['Layyah', 'Karor Lal Esan', 'Choubara'],
            'Jhang' => ['Jhang', 'Shorkot', 'Ahmad Pur Sial', '18-Hazari'],
            'Faisalabad' => ['Faisalabad City', 'Faisalabad Saddar', 'Chak Jhumra', 'Jaranwala', 'Samundari', 'Tandlianwala'],
            'Sargodha' => ['Sargodha', 'Sillanwali', 'Bhalwal', 'Kot Momin', 'Shahpur', 'Sahiwal', 'Bhera'],
            'Mianwali' => ['Mianwali', 'Isa khel', 'Piplan'],
            'Rawalpindi' => ['Rawalpindi Saddar', 'Rawalpindi Cantt', 'Rawalpindi City', 'Kahuta', 'Taxila', 'Kallar Sayeddan', 'Gujjar Khan'],
            'Okara' => ['Okara', 'Renala Khurd', 'Depalpur'],
            'Vehari' => ['Vehari', 'Mailsi', 'Burewala'],
            'Khanewal' => ['Khanewal', 'Mian Channu', 'Kabirwala', 'Jahanian'],
            'Kasur' => ['Kasur', 'Kot Radha Kishan', 'Chunian', 'Pattoki'],
            'Sheikhupura' => ['Sheikhupura', 'Ferozwala', 'Muridke', 'Sharaqpur', 'Safdarabad'],
            'Multan' => ['Multan City', 'Multan Sadar', 'Shujabad', 'Jalalpur Pirwala'],
            'Gujranwala' => ['Gujranwala City', 'Gujranwala Sadar', 'Kamoke', 'Naushera Virkan'],
            'Jhelum' => ['Jhelum', 'Pind Dadan Khan', 'Sohawa', 'Dina'],
            'Toba Tek Singh' => ['Toba Tek Singh', 'Gojra', 'Kamalia', 'Pir Mahal'],
            'Sahiwal' => ['Sahiwal', 'Chichawatni'],
            'Gujrat' => ['Gujrat', 'Kharian', 'Sarai Alamgir', 'Jalalpur Jattan', 'Kunjah'],
            'Sialkot' => ['Sialkot', 'Daska', 'Pasrur', 'Sambrial'],
            'Lodhran' => ['Lodhran', 'Dunyapur', 'Kahror Pacca'],
            'Pakpattan' => ['Pakpattan', 'Arifwala'],
            'Mandi Bahauddin' => ['Mandi Bahauddin', 'Phalia', 'Malakwal'],
            'Chiniot' => ['Chiniot', 'Lalian', 'Bhowana'],
            'Hafizabad' => ['Hafizabad', 'Pindi Bhattian'],
            'Narowal' => ['Narowal', 'Shakargarh', 'Zafarwal'],
            'Nankana Sahib' => ['Nankana Sahib', 'Shahkot', 'Sangla Hill'],
            'Lahore' => ['Lahore City', 'Lahore Cantt', 'Model Town', 'Shalimar', 'Raiwind', 'Allama Iqbal', 'Nishter', 'Saddar', 'Wahga', 'Ravi'],
            'Wazirabad' => ['Wazirabad', 'Alipur Chatha'],
            'Murree' => ['Murree', 'Kotli Sattian'],
            'Kot Addu' => ['Kot Addu', 'Chowk Sarwar Shaheed'],
            'Talagang' => ['Talagang', 'Lawa'],
            'Taunsa' => ['Taunsa', 'Vehova', 'Koh-e-Suleman'],
            'Islamabad' => [
                'Saidpur', 'Noorpur Shahan', 'Mal Pur', 'Kot Hathial', 'Phulgran', 'Pind Begwal', 'Tumair', 'Charah', 'Kirpa', 'Mughal',
                'Rawat', 'Humak', 'Sihala', 'Lohi Bhair', 'Darwala', 'Koral', 'Khana Dak', 'Tarlai Kalan', 'Ali Pur', 'Sohan',
                'Chak Shahzad', 'Kuri', 'Shahrak-e-Rawal', 'Sector F-6', 'Sector G-6', 'Sector F-7', 'Sector F-8', 'Sector F-9',
                'Sector F-10', 'Sector F-11', 'Sector G-7', 'Sector G-8', 'Sector G-9', 'Sector G-10', 'Sector G-11', 'Sector I-8',
                'Sector I-9', 'Sector I-10', 'Maira Sumbal Jafar', 'Bokra', 'Jhangi Saydan', 'Village Noon', 'Tarnol', 'Sarai Kharbooza',
                'Shah Allah Ditta', 'Golra Sharif'
            ],
        ];

        foreach ($data as $districtName => $tehsils) {
            $district = \App\Models\District::create(['name' => $districtName]);
            foreach ($tehsils as $tehsilName) {
                $district->tehsils()->create(['name' => $tehsilName]);
            }
        }
    }
}
