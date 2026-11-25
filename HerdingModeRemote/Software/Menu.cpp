#include <cstdint>
#include <iostream>
#include <string>
#include <cmath>
#include <vector>

#include "./hardware/buzzer.h"
#include "./hardware/potentiometer.h"
#include "./hardware/button.h"
#include "./hardware/display.h"
namespace hw = hardware;
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
}

void sw::Menu::SetOptions(std::vector<std::string> options){
    if (options.size() > 16){
        std::cout << "too many options";
        return;
    }
    for (std::string element : options){
        options_.push_back(element);
    }
}

std::size_t sw::Menu::GetSelection(){
    float slice = 256 / (options_.size()-1);
    return static_cast<size_t>(round((hw::Potentiometer::Read() >> 4)/slice));
}

void sw::Menu::HighlightSelection(std::uint32_t column, std::uint32_t page) const{
    hw::Display::highlight_section(column, column+63, page);
}

void sw::Menu::ShowOptions(){
    hw::Display::clear();
    std::uint32_t column{0};
    std::uint32_t page{0};
    for (std::size_t i{0}; i < options_.size(); ++i){
        page = i/2;
        column = (i%2)*64;

        hw::Display::draw_text(column, page, options_[i]);
        if (i == GetSelection()){
            HighlightSelection(column, page);
        }
    }
    if(!hw::Display::show()){std::cout << "something didn't go through i2c bus\n";}
}

void sw::Menu::Select(){
    std::size_t selection{GetSelection()};
    if(selection < 16){std::cout << options_[selection] << '\n';}  // this will be changed for actual functionality
}