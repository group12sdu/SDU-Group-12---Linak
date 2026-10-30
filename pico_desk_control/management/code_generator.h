//headder file for code generator
#ifndef CODE_GENERATOR_H
#define CODE_GENERATOR_H

#include<string>
#include<random>

namespace management{
    class Code_generator{
        private:
            std::string code_;
            
            std::random_device rd_;
            std::mt19937 gen_;
            
            std::uniform_int_distribution<> distrib_;
        public:
            Code_generator();
            ~Code_generator();
            void generate_code();
            std::string get_code();
    };
}
#endif