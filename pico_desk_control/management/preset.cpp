//preset class
#include "preset.h"

management::Preset::Preset(const int& standing_height, const int& sitting_height) 
    : standing_height_{standing_height}, sitting_height_{sitting_height}
    {
        //constructor
    }

management::Preset::~Preset() = default; //destructor

void management::Preset::edit_preset(const int& standing_height, const int& sitting_height){
    standing_height_ = standing_height;
    sitting_height_ = sitting_height;
}

int management::Preset::get_standing_height() const{
    return standing_height_;
}

int management::Preset::get_sitting_height() const{
    return sitting_height_;
}