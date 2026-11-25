#include <cstdint>
#include <iostream>
#include <string>
#include <cmath>

#include "./hardware/buzzer.h"
#include "./hardware/potentiometer.h"
#include "./hardware/button.h"
#include "./hardware/display.h"
namespace hw = hardware;
#include "./hardware/hardware_constants.h"
namespace hwc = hardware_constants;
#include "Menu.h"
namespace sw = software;

namespace{
    volatile std::uint16_t count{0};
}

sw::Menu::Menu(){
    if (++count > 1){std::cout << "Why are you doing this?\n";}
    hw::Buzzer{};
    hw::Potentiometer{};
    hw::Button{};
    hw::Display{};
    hw::Display::clear();
    hw::Display::show();
    std::cout << count << '\n';
}

void sw::Menu::SetOptions(std::string options[]){
    std::size_t options_size{static_cast<size_t>(sizeof(*options)/sizeof(options))};
    std::size_t options_count{0};
    std::cout << options_size << ' ' << sizeof(options) << ' ' << sizeof(*options) << '\n';
    for (std::size_t i{0}; i < options_size; ++i){
        if(options[i] != ""){
            if(++options_count < 8){
                options_[options_count] = options[i];
            }
            else{
                std::cout << "it do be overflowin'";
            }
        }
    }
    options_count_ = options_count;
}

std::size_t sw::Menu::GetSelection(){
    float slice = hwc::ADC_MAX / options_count_;
    return static_cast<size_t>(round(hw::Potentiometer::Read()/slice));
}

void sw::Menu::HighlightSelection(std::uint32_t column, std::uint32_t page) const{
    hw::Display::highlight_section(column, column+63, page);
}

void sw::Menu::ShowOptions(){
    hw::Display::clear();
    std::uint32_t column{0};
    std::uint32_t page{0};
    for (std::size_t i{0}; i < options_count_; ++i){
        if ((i < (options_count_/2)) && (column != 0)){column = 0;}
        else if ((i > (options_count_/2)) && (column != 64)){column = 64;}
        
        page = i % 2;

        hw::Display::draw_text(column, page, options_[i]);
        if (i == GetSelection()){
            HighlightSelection(column, page);
        }
    }
    std::cout << options_[0] << '\n';
    if(!hw::Display::show()){std::cout << "something didn't go through i2c bus\n";}
}

void sw::Menu::Select(){
    std::size_t selection{GetSelection()};
    if(selection < 8){std::cout << options_[selection] << '\n';}  // this will be changed for actual functionality
}