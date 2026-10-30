//headder file for the desk class
#ifndef DESK_H
#define DESK_H

#include "Preset.h"
#include "Special_mode.h"

namespace management{
    class Desk{
        public:
            Preset preset_;
            Special_mode cleaning_;
            Special_mode presentation_;
            bool is_in_normal_mode_;
            bool is_at_standing_height_;
            
            Desk(const Preset& preset, const Special_mode& cleaning, const Special_mode& presentation);
            ~Desk();
    };
}
#endif