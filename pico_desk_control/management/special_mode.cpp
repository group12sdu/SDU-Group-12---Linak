//special mode class
#include <string>

#include "special_mode.h"

management::Special_mode::Special_mode(const int& height, const std::string& name)
    : height_{height}, name_{name}
    {
        //constructor
    }

management::Special_mode::~Special_mode() = default; // destructor

void management::Special_mode::edit_special_mode(const int& height, const std::string& name){
    height_ = height;
    name_ = name;
}

int management::Special_mode::get_height() const{
    return height_;
}

std::string management::Special_mode::get_name() const{
    return name_;
}