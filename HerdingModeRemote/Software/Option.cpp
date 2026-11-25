#include <string>
#include <iostream>

#include "Option.h"
namespace sw = software;

sw::Option::Option(std::string option){
    for (std::size_t i{0}; i < 10; ++i){
        option_[i] = option[i];
    }
}

std::string sw::Option::String(){
    std::string ans{"          "};
    for (std::size_t i{0}; i < 10; ++i){
        ans[i] = option_[i];
    }
    return ans;
}